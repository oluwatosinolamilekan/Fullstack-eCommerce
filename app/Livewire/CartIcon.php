<?php

namespace App\Livewire;

use Livewire\Component;


class CartIcon extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cartUpdated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        $this->cartCount = count(session('cart', []));
    }


    public function render()
    {
        $cart = session()->get('cart', []);
        $itemCount = count($cart);

        return view('livewire.cart-icon', get_defined_vars());
    }
}
