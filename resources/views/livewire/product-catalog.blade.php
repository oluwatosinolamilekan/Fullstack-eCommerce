<div>
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 text-white flex justify-between">
        <a href="#" class="text-lg font-bold">eCommerce</a>

        <a href="{{ route('cart') }}" class="relative flex items-center">
            🛒 Cart
            <span class="ml-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                @livewire('cart-icon')
            </span>
        </a>
    </nav>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Products</h1>

        <!-- Search & Filter -->
        <div class="flex space-x-4 mb-6">
            <input type="text" wire:model.live="search" 
            class="border p-2 w-full md:w-1/2 lg:w-1/3 min-w-60" 
            placeholder="Search products...">

            <select wire:model.live="category" class="border p-2">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="sortBy" class="border p-2">
                <option value="name">Sort by Name</option>
                <option value="price">Sort by Price</option>
            </select>

            <select wire:model.live="sortDirection" class="border p-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

            <!-- Reset Filters Button -->
            <button wire:click="resetFilters" class="bg-red-500 text-white px-4 py-2 rounded">
                Reset Filters
            </button>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-500 text-white p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Product Listing -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="border p-4 rounded-lg shadow-lg">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-40 object-cover mb-2">
                    <h2 class="text-lg font-bold">{{ $product->name }}</h2>
                    <p class="text-gray-600">${{ number_format($product->price, 2) }}</p>
                    <button wire:click="addToCart({{ $product->id }})"
                        class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                        Add to Cart
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
