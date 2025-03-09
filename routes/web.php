<?php

use App\Livewire\Cart;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\Auth\Register;
use App\Livewire\ProductCatalog;
use Illuminate\Support\Facades\Route;


Route::get('/', ProductCatalog::class)->name('home');
Route::get('/cart', Cart::class)->name('cart');
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::post('/logout', Logout::class)->name('logout');
