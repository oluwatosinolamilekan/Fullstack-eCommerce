<div>
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 text-white flex justify-between items-center">
        <a href="{{route('home')}}" class="text-lg font-bold">eCommerce</a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('cart') }}" class="relative flex items-center"> 🛒 Cart @livewire('cart-icon') </a>
            @if(Auth::check())
            <span class="hidden sm:inline">Hello, {{ Auth::user()->name }}</span>
            <livewire:auth.logout />
            @else
            <a href="{{ route('login') }}" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Login</a>
            <a href="{{ route('register') }}" class="bg-green-500 text-white px-3 py-1 rounded text-sm">Register</a>
            @endif
        </div>
    </nav>
</div>
