<?php

use App\Livewire\Cart;
use App\Livewire\ProductCatalog;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     session()->flush();
//     return view('welcome');
// });

Route::get('/', ProductCatalog::class);
Route::get('/cart', Cart::class)->name('cart');

// Route::get('/order', OrderForm::class);