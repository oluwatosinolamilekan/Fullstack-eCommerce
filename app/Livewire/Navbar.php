<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Navbar extends Component
{
    public function render()
    {
        return view('livewire.navbar',[
            'user' => Auth::user(),
        ]);
    }
}
