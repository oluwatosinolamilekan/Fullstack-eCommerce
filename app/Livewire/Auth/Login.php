<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function mount()
    {
        if (auth()->check()) {
            return redirect()->route('home'); // Redirect logged-in users
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(
            ['email' => $this->email, 
            'password' => $this->password,
        ], $this->remember)) {
            $this->dispatch('showAlert', message: 'Login successfully', type: 'login');
            return redirect()->intended('/');  // Redirect to the dashboard after successful login
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }


    public function render()
    {
        if(auth()->check()){
            return redirect('/');
        }
        return view('livewire.auth.login');
    }
}
