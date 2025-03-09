<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate(); // Invalidate session
        session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login'); // Redirect after logout
    }
    
    public function render()
    {
        return view('livewire.auth.logout');
    }
}
