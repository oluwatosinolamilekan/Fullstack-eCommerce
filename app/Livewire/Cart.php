<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use App\Enums\OrderStatus;

class Cart extends Component
{
    public $cart = [];

    public $customer_name;
    public $customer_email;
    
    protected $listeners = ['cartUpdated' => 'loadCart'];
    

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cart = session('cart', []);
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $this->loadCart();
        $this->dispatch('cartUpdated'); 

    }

    public function placeOrder()
    {

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'status' => OrderStatus::PENDING->value, // Assuming OrderStatus is an Enum
        ]);

        foreach ($cart as $productId => $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'name' => $cartItem['name'],
                'price' => $cartItem['price'],
                'quantity' => $cartItem['quantity'],
            ]);
        }

        session()->forget('cart');

        session()->flash('success', 'Your order has been placed successfully!');

        $this->dispatch('cartUpdated');
    }

    public function increaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
            session()->put('cart', $cart);
        }

        $this->loadCart();
        $this->dispatch('cartUpdated'); 
    }

    public function decreaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity'] -= 1;
                session()->put('cart', $cart);
            } else {
                unset($cart[$productId]); // If quantity is 1 and decreased, remove item from cart
                session()->put('cart', $cart);
            }
        }

        $this->loadCart();
        $this->dispatch('cartUpdated'); 
    }


    public function render()
    {
        return view('livewire.cart');
    }
    
}
