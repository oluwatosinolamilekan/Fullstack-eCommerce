{{-- <div>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4 text-center">Register</h2>

    <form wire:submit.prevent="register">
        <!-- Name Input -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-semibold">Name</label>
            <input wire:model="name" type="text" id="name" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your name">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

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

        <!-- Confirm Password Input -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
            <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded" placeholder="Confirm your password">
            @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full py-2 bg-green-500 text-white rounded hover:bg-green-600">
            Register
        </button>

        <div class="mt-4 text-center">
            <p class="text-sm">Already have an account? <a href="{{ route('login') }}" class="text-blue-500">Login here</a></p>
        </div>
    </form>
</div>

</div> --}}


<div>
    <!-- Navbar -->
    <nav class="bg-gray-800 p-4 text-white flex justify-between">
        <a href="{{route('home')}}" class="text-lg font-bold">eCommerce</a>

        <a href="{{ route('cart') }}" class="relative flex items-center">
            🛒 Cart
            @livewire('cart-icon')  
        </a>

         <!-- Auth Links or User Info -->
         @if(Auth::check())
         <!-- If user is authenticated -->
         <div class="flex items-center space-x-4">
             <span class="text-white">Hello, {{ Auth::user()->name }}</span>
             <form action="{{ route('logout') }}" method="POST">
                 @csrf
                 <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
             </form>
         </div>
     @else
         <!-- If user is not authenticated -->
         <div class="flex items-center space-x-4">
             <a href="{{ route('login') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Login</a>
             <a href="{{ route('register') }}" class="bg-green-500 text-white px-4 py-2 rounded">Register</a>
         </div>
     @endif
    </nav>

    <x-notification />


    <div class="container mx-auto p-6">
        <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-center">Register</h2>
        
            <form wire:submit.prevent="register">
                <!-- Name Input -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold">Name</label>
                    <input wire:model="name" type="text" id="name" class="w-full p-2 border border-gray-300 rounded" placeholder="Enter your name">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
        
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
        
                <!-- Confirm Password Input -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-semibold">Confirm Password</label>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" class="w-full p-2 border border-gray-300 rounded" placeholder="Confirm your password">
                    @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
        
                <button type="submit" class="w-full py-2 bg-green-500 text-white rounded hover:bg-green-600">
                    Register
                </button>
        
                <div class="mt-4 text-center">
                    <p class="text-sm">Already have an account? <a href="{{ route('login') }}" class="text-blue-500">Login here</a></p>
                </div>
            </form>
        </div>
</div>
