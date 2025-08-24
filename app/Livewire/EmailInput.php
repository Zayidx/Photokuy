<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;

class EmailInput extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function saveEmail()
    {
        // Normalize before validation
        $this->email = Str::of($this->email)->trim()->lower();
        $this->validate();

        session(['photobooth_email' => (string) $this->email]);

        return $this->redirect(route('photobooth.select'), navigate: true);
    }

    public function render()
    {
        return view('livewire.email-input')->layout('layouts.app');
    }
}
