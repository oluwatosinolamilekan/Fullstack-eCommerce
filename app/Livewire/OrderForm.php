<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use Livewire\Component;

class OrderForm extends Component
{
    public $name, $email, $product_id, $quantity = 1;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
    ];

    public function placeOrder()
    {
        $this->validate();

        Order::create([
            'customer_name' => $this->name,
            'customer_email' => $this->email,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'status' => 'pending',
        ]);

        session()->flash('message', 'Order placed successfully!');
        $this->reset();
    }

    public function render()
    {
        $products = Product::latest()->get();

        return view('livewire.order-form', get_defined_vars());
    }
}
