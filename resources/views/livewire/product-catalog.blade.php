<div>
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
            <a href="{{route('home')}}" class="text-lg font-bold">eCommerce</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('cart') }}" class="relative flex items-center"> 🛒 Cart @livewire('cart-icon') </a>
                @if(Auth::check())
                <span class="hidden sm:inline">Hello, {{ Auth::user()->name }}</span>
                <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded text-sm">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Login</a>
                <a href="{{ route('register') }}" class="bg-green-500 text-white px-3 py-1 rounded text-sm">Register</a>
                @endif
            </div>
        </nav>
    
        <!-- Main Content -->
        <div class="container mx-auto p-4 flex flex-col md:flex-row gap-4 flex-1">
            <!-- Sidebar Filter (Collapsible on Small Screens) -->
            <aside class="w-full md:w-1/4 bg-gray-100 p-4 shadow-md md:h-auto rounded-md">
                <h2 class="text-lg font-semibold mb-4">Filter Products</h2>
                <div class="mb-4">
                    <input id="search" type="text" wire:model.live="search" class="border p-2 w-full rounded-md" placeholder="Search...">
                </div>
                <div class="mb-4">
                    <select id="category" wire:model.live="category" class="border p-2 w-full rounded-md">
                        <option value="">All Categories</option>
                        @foreach($categories as $parentCategory)
                            <!-- Parent category as selectable -->
                            <option value="{{ $parentCategory->id }}" class="font-bold">{{ $parentCategory->name }}</option>
                            
                            @foreach($parentCategory->children as $childCategory)
                                <!-- Child category as selectable -->
                                <option value="{{ $childCategory->id }}">— {{ $childCategory->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <select id="sortBy" wire:model.live="sortBy" class="border p-2 w-full rounded-md">
                        <option value="name">Name</option>
                        <option value="price">Price</option>
                    </select>
                </div>
                <div>
                    <button wire:click="resetFilters" class="bg-red-500 text-white px-4 py-2 w-full rounded-md">Reset Filters</button>
                </div>
            </aside>
    
            <!-- Product Listings -->
            <main class="w-full md:w-3/4 p-4">
                <h1 class="text-2xl font-bold mb-4">Products</h1>
                @if(session()->has('success') || session()->has('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" 
                    class="fixed top-4 right-4 max-w-xs w-full bg-white shadow-lg rounded-lg p-4 border-l-4 
                    {{ session()->has('success') ? 'border-green-500' : 'border-red-500' }}">
                    
                    <div class="flex items-start">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ session('success') ?? session('error') }}
                            </p>
                        </div>
                        <button @click="show = false" class="ml-3 text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                </div>
            @endif
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    @php
                        $cart = session('cart', []);
                        $isInCart = array_key_exists($product->id, $cart);
                        $quantity = $cart[$product->id]['quantity'] ?? 1;
                    @endphp
                    <div class="border p-4 rounded-lg shadow bg-white">
                        <img src="{{ Str::startsWith($product->image, 'http') ? $product->image : asset('storage/products/' . basename($product->image)) }}" 
                        alt="{{ $product->name }}" class="w-full h-40 object-cover rounded" >
                        <h3 class="text-lg font-semibold mt-2">{{ Str::limit($product->name, 10) }}</h3>
                        <p class="text-gray-600">${{ $product->price }}</p>
                        @if($isInCart)
                        <div class="flex items-center mt-2 space-x-1">
                            <button wire:click="decreaseQuantity({{ $product->id }})" 
                                    class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">-</button>
                            <input type="text" value="{{ $quantity }}" 
                                   class="w-10 text-center border rounded text-xs px-2 py-1" readonly>
                            <button wire:click="increaseQuantity({{ $product->id }})" 
                                    class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs">+</button>
                        </div>
                        <button wire:click="removeFromCart({{ $product->id }})"
                            class="mt-2 bg-red-500 text-white px-3 py-1 rounded w-full text-xs">Remove</button>
                    @else
                        <button wire:click="addToCart({{ $product->id }})"
                            class="mt-2 bg-blue-500 text-white px-3 py-1 rounded w-full text-xs">Add to Cart</button>
                    @endif
                        {{-- <button wire:click="addToCart({{ $product->id }})" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded text-sm">Add to Cart</button> --}}
                    </div>
                    @endforeach
                </div>
                <!-- Pagination -->
                <div class="flex justify-between mt-4">
                    <button 
                    wire:click="previousPage" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 border rounded-md text-sm font-medium text-gray-600 bg-white hover:bg-gray-100 disabled:opacity-50"
                    @if($products->onFirstPage()) disabled @endif
                >
                    &larr; Previous
                </button>
            
                <div class="flex space-x-1">
                    @foreach ($products->links()->elements[0] as $page => $url)
                        <button 
                            wire:click="gotoPage({{ $page }})"
                            class="px-3 py-1 border rounded-md text-sm font-medium 
                            {{ $page == $products->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-600 bg-white hover:bg-gray-100' }}">
                            {{ $page }}
                        </button>
                    @endforeach
                </div>
            
                <button 
                    wire:click="nextPage" 
                    wire:loading.attr="disabled"
                    class="px-4 py-2 border rounded-md text-sm font-medium text-gray-600 bg-white hover:bg-gray-100 disabled:opacity-50"
                    @if(!$products->hasMorePages()) disabled @endif
                >
                    Next &rarr;
                </button>
                </div>
            </main>
        </div>
    </div>
    
</div>
