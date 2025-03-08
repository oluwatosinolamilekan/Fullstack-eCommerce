<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'category', 'sortBy', 'sortDirection']);
        $this->resetPage();
    }

    public function orderProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            session()->flash('error', 'Product not found.');
            return;
        }

        // Here, you can implement your order logic
        // Example: Adding the product to an order session or database
        session()->push('cart', $productId); // Simple example

        session()->flash('success', "{$product->name} has been added to your cart!");
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated'); 
        session()->flash('success', 'Product added to cart!');
    }

    public function removeFromCart($productId)
    {
        $cart = session()->get('cart', []);
    
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    
        session()->flash('success', 'Product removed from cart!');
    }

    public function placeOrder()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            session()->flash('error', 'Cart is empty!');
            return;
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total' => array_sum(array_column($cart, 'price')),
        ]);

        foreach ($cart as $cartItem) {
            $order->products()->attach($cartItem['id'], ['price' => $cartItem['price']]);
        }

        session()->forget('cart');
        session()->flash('success', 'Order placed successfully!');
    }

    public function render()
    {
        $allowedSortFields = ['name', 'price'];
        if (!in_array($this->sortBy, $allowedSortFields)) {
            $this->sortBy = 'name';
        }

        $products = Product::query()
        ->when($this->search, function ($query) {
            $query->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%");
        })
      
        ->when($this->category, function ($query) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->category); 
            });
        })
        ->orderBy($this->sortBy, $this->sortDirection)
        ->paginate(10);

        // $products = Product::query()
        // ->when($this->search, fn($query) => $query->where('name', 'like', "%{$this->search}%"))
        // ->when($this->category, fn($query) => $query->whereHas('categories', fn($q) => $q->where('id', $this->category)))
        // ->orderBy($this->sortBy, $this->sortDirection)
        // ->paginate(10);

        $categories = Category::all();

        return view('livewire.product-catalog', get_defined_vars());
    }
}
