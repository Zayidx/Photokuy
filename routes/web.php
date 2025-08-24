<?php


use App\Livewire\EditFoto;
use App\Livewire\EmailInput;
use App\Livewire\LayoutSelector;
use App\Livewire\Photobooth;
use App\Livewire\ReviewPhotos;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    // Collect recent photobooth photos from public storage (strips first, then photos)
    $disk = Storage::disk('public');
    $candidates = [];
    foreach (['strips', 'photos'] as $dir) {
        if ($disk->exists($dir)) {
            foreach ($disk->files($dir) as $file) {
                if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
                    $path = $disk->path($file);
                    $mtime = @filemtime($path) ?: 0;
                    $candidates[] = ['file' => $file, 'mtime' => $mtime];
                }
            }
        }
    }
    // Sort by modified time, newest first, and limit
    usort($candidates, fn($a,$b) => $b['mtime'] <=> $a['mtime']);
    $candidates = array_slice($candidates, 0, 20);
    $bgPhotos = array_map(fn($it) => $disk->url($it['file']), $candidates);

    return view('welcome', ['bgPhotos' => $bgPhotos]);
});

// Step 1: Email Input
Route::get('/photobooth', EmailInput::class)->name('photobooth.start');

// Step 2: Layout Selection
Route::get('/photobooth/select-layout', LayoutSelector::class)->name('photobooth.select');

// Step 3: Edit/Customize Photo Settings
Route::get('/photobooth/{layout}/edit', EditFoto::class)->name('photobooth.edit');

// Step 4: Photo Capture
Route::get('/photobooth/{layout}/capture', Photobooth::class)->name('photobooth.capture');

// Step 5: Review & Retake Selected
Route::get('/photobooth/review', ReviewPhotos::class)->name('photobooth.review');



Route::get('/photo/{filename}', function ($filename) {
    // Basic security: prevent directory traversal
    $filename = basename($filename);
    
    $path = 'strips/' . $filename;
    if (!Storage::disk('public')->exists($path)) {
        $path = 'photos/' . $filename;
        if(!Storage::disk('public')->exists($path)){
             abort(404);
        }
    }

    $photoUrl = Storage::url($path);

    return view('photo', ['photoUrl' => $photoUrl]);
})->name('photo.view');
