<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ToastNotification extends Component
{
    public $show = false;
    public $message = '';
    public $type = 'success'; // success, error, warning, info

    #[On('show-toast')]
    public function showToast($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;

        // Auto-hide after 3 seconds
        $this->dispatch('hide-toast-after-delay');
    }

    public function hide()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.toast-notification');
    }
}
