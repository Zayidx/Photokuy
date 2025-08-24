<?php

namespace App\Livewire;

use Livewire\Component;

class EditFoto extends Component
{
    public $layout;
    public $countdown = 3;
    public $mirrorMode = false;
    public $filter = 'normal';
    public $cameraDeviceId = null; // optional selected camera deviceId

    public $filters = [
        'normal' => 'Normal',
        'bw' => 'B&W',
        'sepia' => 'Sepia',
        'vintage' => 'Vintage',
    ];

    public function mount($layout)
    {
        $this->layout = $layout;
    }

    public function startShooting()
    {
        // Basic validation before proceeding
        $validated = validator([
            'countdown' => $this->countdown,
            'mirrorMode' => $this->mirrorMode,
            'filter' => $this->filter,
        ], [
            'countdown' => 'required|in:3,5,10',
            'mirrorMode' => 'required|boolean',
            'filter' => 'required|in:normal,bw,sepia,vintage',
        ])->validate();

        session(['photobooth_settings' => $validated, 'photobooth_deviceId' => $this->cameraDeviceId]);

        return $this->redirect(route('photobooth.capture', ['layout' => $this->layout]), navigate: true);
    }

    public function render()
    {
        return view('livewire.edit-foto')->layout('layouts.app');
    }
}
