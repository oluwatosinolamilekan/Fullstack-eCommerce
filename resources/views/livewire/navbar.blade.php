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
</div>
