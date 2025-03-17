<nav class="bg-gray-800 p-4 text-white flex justify-between">
    <a href="{{route('home')}}" class="text-lg font-bold">eCommerce</a>

    <a href="{{ route('cart') }}" class="relative flex items-center">
        🛒 Cart
        @livewire('cart-icon')  
    </a>
</nav>
