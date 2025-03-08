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
        // if (count($this->cart) > 0) {

            
        //     // Save order to database (Example)
        //     session()->forget('cart');
        //     $this->dispatch('cartUpdated'); 
        //     session()->flash('success', 'Your order has been placed successfully!');
        // }

        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        $order = Order::create([
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
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

        // Clear the cart after placing the order
        session()->forget('cart');

        // Provide feedback to the user
        session()->flash('success', 'Your order has been placed successfully!');

        // Dispatch event for cart updated (if needed)
        $this->dispatch('cartUpdated');
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
