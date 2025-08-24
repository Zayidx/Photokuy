<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LayoutSelector extends Component
{
    public $layouts = [
        '4x1' => '4 Photos (Strip)',
        '3x1' => '3 Photos (Strip)',
        '2x1' => '2 Photos (Strip)',
        '3x2' => '6 Photos (Grid)',
    ];

    public function selectLayout($layout)
    {
        if (!isset($this->layouts[$layout])) {
            return;
        }
        session(['photobooth_layouts' => $this->layouts]);
        Log::info('Layout selected: ' . $layout . '. Redirecting to edit page.');
        return $this->redirect(route('photobooth.edit', ['layout' => $layout]));
    }

    public function render()
    {
        return view('livewire.layout-selector')->layout('layouts.app');
    }
}
