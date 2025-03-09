
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
        <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>
    
            <form wire:submit.prevent="login">
                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold">Email</label>
                    <input wire:model="email" type="email" id="email" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your email">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
    
                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold">Password</label>
                    <input wire:model="password" type="password" id="password" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your password">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
    
                <!-- Remember Me Checkbox -->
                <div class="flex items-center mb-4">
                    <input wire:model="remember" type="checkbox" id="remember" class="mr-2">
                    <label for="remember" class="text-sm">Remember Me</label>
                </div>
    
                <button type="submit" class="w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Login
                </button>
    
                <div class="mt-4 text-center">
                    <p class="text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500">Register here</a></p>
                </div>
            </form>
      
    </div>
</div>
