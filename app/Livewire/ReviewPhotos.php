<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Throwable;

class ReviewPhotos extends Component
{
    public $step = 'review'; // review, finalizing, finished
    public $photos = [];     // relative filenames in public disk, e.g., temp/abc.jpg
    public $photoUrls = [];  // public URLs for blade
    public $layout;
    public $finalStripUrl;
    public $countdown = 3;

    public $layouts = [
        '4x1' => ['rows' => 4, 'cols' => 1, 'total' => 4],
        '3x1' => ['rows' => 3, 'cols' => 1, 'total' => 3],
        '2x1' => ['rows' => 2, 'cols' => 1, 'total' => 2],
        '3x2' => ['rows' => 3, 'cols' => 2, 'total' => 6],
    ];

    public function mount()
    {
        $this->layout = session('photobooth_layout');
        $this->photos = (array) session('photobooth_captures', []);
        if (empty($this->photos)) {
            return $this->redirect(route('photobooth.select'));
        }
        $this->photoUrls = array_map(function ($rel) {
            return Storage::disk('public')->url($rel);
        }, $this->photos);
        $settings = session('photobooth_settings', []);
        $this->countdown = $settings['countdown'] ?? 3;
    }

    public function retake($index, $imageData)
    {
        if (!isset($this->photos[$index])) return;
        if (!is_string($imageData) || !str_starts_with($imageData, 'data:image/jpeg;base64,')) {
            return;
        }
        $raw = substr($imageData, strlen('data:image/jpeg;base64,'));
        $binary = base64_decode($raw, true);
        if ($binary === false || strlen($binary) < 1000) {
            return;
        }
        // Store replacement
        $newFilename = 'temp/' . Str::random(40) . '.jpg';
        $ok = Storage::disk('public')->put($newFilename, $binary);
        if (!$ok) return;

        // Delete old file
        $old = $this->photos[$index];
        if ($old && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }

        // Update arrays and session
        $this->photos[$index] = $newFilename;
        $this->photoUrls[$index] = Storage::disk('public')->url($newFilename);
        session(['photobooth_captures' => $this->photos]);
        $this->dispatch('toast', message: 'Photo updated');
    }

    public function finalize()
    {
        if (empty($this->photos)) {
            return $this->redirect(route('photobooth.select'));
        }
        $layout = $this->layout;
        if (!$layout || !isset($this->layouts[$layout])) {
            // Fallback to vertical strip
            $rows = count($this->photos);
            $cols = 1;
        } else {
            $rows = $this->layouts[$layout]['rows'];
            $cols = $this->layouts[$layout]['cols'];
        }

        $paths = array_map(function ($rel) {
            return Storage::disk('public')->path($rel);
        }, $this->photos);

        $dimensions = @getimagesize($paths[0]);
        if ($dimensions === false) {
            Log::error('ReviewPhotos could not read image dimensions');
            return $this->redirect(route('photobooth.select'));
        }
        [$photoWidth, $photoHeight] = $dimensions;

        $stripWidth = $photoWidth * $cols;
        $stripHeight = $photoHeight * $rows;
        $strip = imagecreatetruecolor($stripWidth, $stripHeight);
        $white = imagecolorallocate($strip, 255, 255, 255);
        imagefill($strip, 0, 0, $white);

        $settings = session('photobooth_settings', []);
        $mirrorMode = $settings['mirrorMode'] ?? false;
        $filter = $settings['filter'] ?? 'normal';

        $currentPhotoIndex = 0;
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                if ($currentPhotoIndex >= count($paths)) break;
                $photo = @imagecreatefromjpeg($paths[$currentPhotoIndex]);
                if ($photo === false) { $currentPhotoIndex++; continue; }

                if ($mirrorMode) { imageflip($photo, IMG_FLIP_HORIZONTAL); }
                switch ($filter) {
                    case 'bw': imagefilter($photo, IMG_FILTER_GRAYSCALE); break;
                    case 'sepia': imagefilter($photo, IMG_FILTER_GRAYSCALE); imagefilter($photo, IMG_FILTER_COLORIZE, 100, 50, 0); break;
                    case 'vintage': imagefilter($photo, IMG_FILTER_GRAYSCALE); imagefilter($photo, IMG_FILTER_BRIGHTNESS, -20); imagefilter($photo, IMG_FILTER_COLORIZE, 100, 70, 50); break;
                }
                imagecopy($strip, $photo, $c * $photoWidth, $r * $photoHeight, 0, 0, $photoWidth, $photoHeight);
                imagedestroy($photo);
                $currentPhotoIndex++;
            }
        }

        if (!Storage::disk('public')->exists('strips')) {
            Storage::disk('public')->makeDirectory('strips');
        }
        $finalFileName = 'strips/' . Str::random(40) . '.jpg';
        $finalPath = Storage::disk('public')->path($finalFileName);
        imagejpeg($strip, $finalPath, 90);
        imagedestroy($strip);

        // Cleanup temps
        foreach ($paths as $p) { if (is_file($p)) { @unlink($p); } }
        session()->forget('photobooth_captures');

        $this->finalStripUrl = Storage::disk('public')->url($finalFileName);

        if (config('photobooth.email_enabled')) {
            $this->step = 'finalizing';
            $this->sendEmailAndFinish();
        } else {
            $this->step = 'finished';
            $this->dispatch('toast', message: 'Photo ready to download');
        }
    }

    public function sendEmailAndFinish()
    {
        $email = session('photobooth_email');
        if (empty($this->finalStripUrl)) {
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
                $downloadUrl = route('photo.view', ['filename' => basename($this->finalStripUrl)]);
                $photoUrl = url($this->finalStripUrl);
                Mail::to($email)->send(new SendEmail(
                    subject: 'Your Photobooth Photo Strip!',
                    view: 'mail.photobooth',
                    data: compact('downloadUrl', 'photoUrl')
                ));
                $this->dispatch('toast', message: 'Email sent');
            } catch (Throwable $e) {
                Log::error('ReviewPhotos email failed: ' . $e->getMessage());
                $this->dispatch('toast', message: 'Could not send email');
            } finally {
                session()->forget('photobooth_email');
                session()->forget('photobooth_settings');
            }
        }
        $this->step = 'finished';
    }

    public function resetPhotobooth()
    {
        session()->flash('toast', 'Enjoy your photos!');
        return $this->redirect(route('photobooth.select'));
    }

    public function render()
    {
        return view('livewire.review-photos')->layout('layouts.app');
    }

    public function restartShoot()
    {
        // Delete current temp photos and clear session, then redirect to capture for same layout
        foreach ((array) $this->photos as $rel) {
            if ($rel && Storage::disk('public')->exists($rel)) {
                Storage::disk('public')->delete($rel);
            }
        }
        session()->forget('photobooth_captures');
        $layout = session('photobooth_layout');
        if (!$layout) { return $this->redirect(route('photobooth.select'), navigate: true); }
        return $this->redirect(route('photobooth.capture', ['layout' => $layout]), navigate: true);
    }
}
