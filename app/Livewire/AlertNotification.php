<?php

namespace App\Livewire;

use Livewire\Component;

class AlertNotification extends Component
{
    public $message;
    public $type; // success, error, warning, info

    protected $listeners = ['showAlert' => 'displayAlert'];

    public function displayAlert($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;

        // Dispatch a browser event to show the alert (optional for JS-based alerts)
        $this->dispatch('alert', message: $message, type: $type);

        // Automatically clear the alert after 3 seconds
        $this->dispatch('clearAlert')->self();
    }


    public function render()
    {
        return view('livewire.alert-notification');
    }
}
