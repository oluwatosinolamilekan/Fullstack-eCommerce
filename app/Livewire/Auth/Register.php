<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Enums\UserEnums;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|confirmed|min:6',
    ];

    public function mount()
    {
        if (auth()->check()) {
            return redirect()->route('home'); // Redirect logged-in users
        }
    }

    public function register()
    {
        $this->validate();

       $user =  User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        $this->dispatch('showAlert', message: 'Registration successful! Welcome.', type: 'login');
        return redirect()->route('home');
    }

    
    public function render()
    {
        if(auth()->check()){
            return redirect()->route('home');  // Redirect to login after successful registration
        }
        return view('livewire.auth.register');
    }
}
