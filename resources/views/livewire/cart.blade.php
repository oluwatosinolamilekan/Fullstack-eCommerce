<div>
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 text-white flex justify-between">
        <a href="{{route('home')}}" class="text-lg font-bold">eCommerce</a>

        <a href="{{ route('cart') }}" class="relative flex items-center">
            🛒 Cart
            @livewire('cart-icon')  
        </a>
    </nav>

    <x-notification />


    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">eCommerce</h1>

        @if(count($cart) > 0)
        <ul class="space-y-4">
            @foreach($cart as $productId => $cartItem)
                <li class="flex items-center justify-between bg-gray-50 p-4 rounded-lg shadow-md">
                    <div class="flex items-center space-x-4">
                        <img src="{{ Str::startsWith($cartItem['image'], 'http') ? $cartItem['image'] : asset('storage/products/' . basename($cartItem['image'])) }}"
                            alt="{{ $cartItem['name'] }}"
                            class="w-16 h-16 object-cover rounded-lg">
                        <div>
                            <h3 class="text-xl font-semibold">{{ $cartItem['name'] }}</h3>
                            <p class="text-gray-500">${{ number_format($cartItem['price'], 2) }}</p>
                        </div>
                    </div>
        
                    <!-- Quantity Controls -->
                    <div class="flex items-center space-x-2">
                        <button wire:click="decreaseQuantity({{ $productId }})" 
                                class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 transition">
                            -
                        </button>
                        
                        <span class="text-gray-600">{{ $cartItem['quantity'] }}</span>
        
                        <button wire:click="increaseQuantity({{ $productId }})" 
                                class="bg-gray-200 text-gray-700 px-3 py-1 rounded hover:bg-gray-300 transition">
                            +
                        </button>
        
                        <!-- Remove Item Button -->
                        <button wire:click="removeFromCart({{ $productId }})" 
                                class="bg-red-500 text-white px-4 py-2 rounded-full">
                            Remove
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
        

            <!-- Customer Information Form -->
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Order for your carts</h2>

                <form wire:submit.prevent="placeOrder">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Place Order
                    </button>
                </form>
            </div>
        @else
            <p class="text-gray-600 text-center">Your cart is empty. Add some products!</p>
        @endif
    </div>
</div>
