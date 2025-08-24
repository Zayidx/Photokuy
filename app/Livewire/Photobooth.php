<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Resend;
use Throwable;

class Photobooth extends Component
{
    public $step = 'capturing'; // capturing, sending_email, finished
    public $layout;
    public $layouts = [
        '4x1' => ['rows' => 4, 'cols' => 1, 'total' => 4],
        '3x1' => ['rows' => 3, 'cols' => 1, 'total' => 3],
        '2x1' => ['rows' => 2, 'cols' => 1, 'total' => 2],
        '3x2' => ['rows' => 3, 'cols' => 2, 'total' => 6],
    ];

    public $photos = [];
    public $captureCount = 0;
    public $totalCaptures = 0;
    public $finalStripUrl;

    // Settings from EditFoto component
    public $countdown = 3;
    public $mirrorMode = false;
    public $filter = 'normal';
    public $cameraDeviceId = null;

    public function mount($layout)
    {
        if (!isset($this->layouts[$layout])) {
            return redirect()->route('photobooth.select');
        }
        $this->layout = $layout;
        session(['photobooth_layout' => $layout]);
        $this->totalCaptures = $this->layouts[$layout]['total'];

        $settings = session('photobooth_settings', []);
        $this->countdown = $settings['countdown'] ?? 3;
        $this->mirrorMode = $settings['mirrorMode'] ?? false;
        $this->filter = $settings['filter'] ?? 'normal';
        $this->cameraDeviceId = session('photobooth_deviceId');
    }

    public function capture($imageData)
    {
        // Basic validation: expect a JPEG data URL and valid base64
        if (!is_string($imageData) || !str_starts_with($imageData, 'data:image/jpeg;base64,')) {
            Log::warning('Photobooth capture rejected: invalid image data prefix');
            return; // ignore malformed input to avoid breaking the flow
        }

        $raw = substr($imageData, strlen('data:image/jpeg;base64,'));
        $binary = base64_decode($raw, true);
        if ($binary === false || strlen($binary) < 1000) { // guard against tiny/invalid payloads
            Log::warning('Photobooth capture rejected: invalid or too small image payload');
            return;
        }

        $filename = 'temp/' . Str::random(40) . '.jpg';
        $ok = Storage::disk('public')->put($filename, $binary);
        if (!$ok) {
            Log::error('Photobooth failed to store captured image');
            return;
        }

        // Track in-memory for this component
        $this->photos[] = Storage::disk('public')->path($filename);
        // Also persist relative filename to session so a review component can load them later
        $sessionPhotos = (array) session('photobooth_captures', []);
        $sessionPhotos[] = $filename; // store relative path within public disk
        session(['photobooth_captures' => $sessionPhotos]);
        $this->captureCount++;

        if ($this->captureCount >= $this->totalCaptures) {
            // Defer processing to the review component
            return $this->redirect(route('photobooth.review'), navigate: true);
        }
    }

    public function processStrip()
    {
        if (empty($this->photos)) {
            return $this->redirect(route('photobooth.select'));
        }

        $layoutInfo = $this->layouts[$this->layout];
        $rows = $layoutInfo['rows'];
        $cols = $layoutInfo['cols'];

        $dimensions = @getimagesize($this->photos[0]);
        if ($dimensions === false) {
            Log::error('Photobooth could not read image dimensions');
            return $this->redirect(route('photobooth.select'));
        }
        [$photoWidth, $photoHeight] = $dimensions;

        $stripWidth = $photoWidth * $cols;
        $stripHeight = $photoHeight * $rows;

        $strip = imagecreatetruecolor($stripWidth, $stripHeight);
        $white = imagecolorallocate($strip, 255, 255, 255);
        imagefill($strip, 0, 0, $white);

        $currentPhotoIndex = 0;
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                if ($currentPhotoIndex >= count($this->photos)) break;

                $photoPath = $this->photos[$currentPhotoIndex];
                $photo = @imagecreatefromjpeg($photoPath);
                if ($photo === false) {
                    Log::error('Photobooth failed to create image from JPEG: ' . $photoPath);
                    $currentPhotoIndex++;
                    continue;
                }

                if ($this->mirrorMode) {
                    imageflip($photo, IMG_FLIP_HORIZONTAL);
                }

                switch ($this->filter) {
                    case 'bw':
                        imagefilter($photo, IMG_FILTER_GRAYSCALE);
                        break;
                    case 'sepia':
                        imagefilter($photo, IMG_FILTER_GRAYSCALE);
                        imagefilter($photo, IMG_FILTER_COLORIZE, 100, 50, 0);
                        break;
                    case 'vintage':
                         imagefilter($photo, IMG_FILTER_GRAYSCALE);
                         imagefilter($photo, IMG_FILTER_BRIGHTNESS, -20);
                         imagefilter($photo, IMG_FILTER_COLORIZE, 100, 70, 50);
                         break;
                }

                imagecopy($strip, $photo, $c * $photoWidth, $r * $photoHeight, 0, 0, $photoWidth, $photoHeight);
                imagedestroy($photo);
                $currentPhotoIndex++;
            }
        }

        $finalFileName = 'strips/' . Str::random(40) . '.jpg';
        $finalPath = Storage::disk('public')->path($finalFileName);
        
        if (!Storage::disk('public')->exists('strips')) {
            Storage::disk('public')->makeDirectory('strips');
        }

        imagejpeg($strip, $finalPath, 90);
        imagedestroy($strip);

        foreach ($this->photos as $tempPhoto) {
            if (is_file($tempPhoto)) {
                @unlink($tempPhoto);
            }
        }

        $this->finalStripUrl = Storage::url($finalFileName);
        if (config('photobooth.email_enabled')) {
            $this->step = 'sending_email';
        } else {
            $this->step = 'finished';
            $this->dispatch('toast', message: 'Photo ready to download');
        }
    }

    public function sendEmailAndFinish()
    {
        $email = session('photobooth_email');
        if (empty($this->finalStripUrl)) {
            Log::warning('sendEmailAndFinish called without finalStripUrl');
            $this->step = 'finished';
            return;
        }
        if (!config('photobooth.email_enabled')) {
            $this->dispatch('toast', message: 'Photo ready to download');
            $this->step = 'finished';
            return;
        }
        if ($email) {
            try {
                $apiKey = env('RESEND_API_KEY');
                if (empty($apiKey)) {
                    Log::warning('RESEND_API_KEY missing; skipping email dispatch');
                } else {
                    $resend = Resend::client($apiKey);
                }
                $downloadUrl = route('photo.view', ['filename' => basename($this->finalStripUrl)]);
                $photoUrl = url($this->finalStripUrl);

                $htmlContent = "<h1>Here's Your Photo Strip!</h1><p>Thanks for using our photobooth. Click the image or the button below to see and download your photo.</p><a href='{$downloadUrl}'><img src='{$photoUrl}' alt='Your Photo Strip' style='max-width: 100%;'></a><br><a href='{$downloadUrl}' style='display: inline-block; padding: 12px 25px; margin: 20px 0; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 5px;'>View & Download</a>";

                if (!empty($apiKey)) {
                    $resend->emails->send([
                        'from' => 'Photobooth <onboarding@resend.dev>',
                        'to' => $email,
                        'subject' => 'Your Photobooth Photo Strip!',
                        'html' => $htmlContent,
                    ]);
                    Log::info('Resend SDK send command executed successfully for: ' . $email);
                    $this->dispatch('toast', message: 'Email sent');
                } else {
                    $this->dispatch('toast', message: 'Photo ready to download');
                }

            } catch (Throwable $e) {
                Log::error('Photobooth email failed to send via Resend: ' . $e->getMessage());
                $this->dispatch('toast', message: 'Could not send email');
            } finally {
                session()->forget('photobooth_email');
                session()->forget('photobooth_settings');
            }
        }
        $this->step = 'finished';
    }

    public function undoLastCapture()
    {
        if ($this->step !== 'capturing') {
            return;
        }
        if (empty($this->photos)) {
            return;
        }
        $last = array_pop($this->photos);
        if (is_file($last)) {
            @unlink($last);
        }
        if ($this->captureCount > 0) {
            $this->captureCount--;
        }
    }
    
    public function resetPhotobooth()
    {
        session()->flash('toast', 'Enjoy your photos!');
        return $this->redirect(route('photobooth.select'));
    }

    public function render()
    {
        return view('livewire.photobooth')->layout('layouts.app');
    }
}
