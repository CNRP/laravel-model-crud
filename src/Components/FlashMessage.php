<?php

namespace Cnrp\ModelCRUD\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class FlashMessage extends Component
{
    public $message;
    public $type;

    protected $listeners = ['flashMessage'];

    #[On('flashMessage')]
    public function flashMessage($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;

    }

    public function render()
    {
        return view('modelcrud::livewire.flash-message');
    }
}
