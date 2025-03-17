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
    public $showFilterModal = false; // Define the property here

    // Other properties and methods

    

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

    public function toggleFilterModal()
    {
        $this->showFilterModal = !$this->showFilterModal;
    }

    public function orderProduct($productId)
    {
        if (!auth()->check()) {
            session()->flash('error', 'You must be logged in to place an order.');
            return;
        }

        $product = Product::find($productId);

        if (!$product) {
            session()->flash('error', 'Product not found.');
            return;
        }

        session()->push('cart', $productId); // Simple example

        session()->flash('success', "{$product->name} has been added to your cart!");
    }

    public function addToCart($productId)
    {
        if (!auth()->check()) {
            $this->dispatch('showAlert', message: 'You must be logged in to add items to the cart.', type: 'error');
            return;
        }

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
        $this->cart = $cart; // Update Livewire state
        $this->dispatch('cartUpdated'); 
        $this->dispatch('showAlert', message: 'Product added to cart!', type: 'success');
    }

    public function increaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        }

        session()->put('cart', $cart);
        $this->dispatch('cartUpdated'); 
        $this->dispatch('showAlert', message: 'Quantity increase successfully', type: 'success');
    }


    public function decreaseQuantity($productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            $this->dispatch('showAlert', message: 'Product not found in cart!', type: 'error');
            return;
        }

        if ($cart[$productId]['quantity'] > 1) {
            $cart[$productId]['quantity']--;
            session()->put('cart', $cart);
            $this->dispatch('cartUpdated'); 
            $this->dispatch('showAlert', message: 'Quantity decreased successfully', type: 'success');
        } else {
            // Prevent decreasing below 1
            $this->dispatch('showAlert', message: 'Quantity cannot be less than 1', type: 'warning');
        }
    }


    public function removeFromCart($productId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
            $this->dispatch('showAlert', message: 'You must be logged in to add items to the cart', type: 'error');
            return;
        }


        $cart = session()->get('cart', []);
    
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        $this->dispatch('cartUpdated'); 
        $this->dispatch('showAlert', message: 'Product removed from cart!', type: 'success');
    }

    public function placeOrder()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
            $this->dispatch('showAlert', message: 'You must be logged in to add items to the cart.', type: 'error');
            return;
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            $this->dispatch('showAlert', message: 'Cart is empty!', type: 'error');
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
        $this->dispatch('showAlert', message: 'Order placed successfully!', type: 'success');
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

        // $categories = Category::with('children')->get(); // Eager load children categories
        $categories = Category::with(['children' => function ($query) {
            $query->orderBy('name', 'asc'); // Sort child categories
        }])
        ->orderBy('name', 'asc') // Sort parent categories
        ->get();

        return view('livewire.product-catalog', get_defined_vars());
    }
}
