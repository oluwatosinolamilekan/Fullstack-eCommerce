<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Order a Product</h2>
    
        @if(session()->has('message'))
            <div class="bg-green-500 text-white p-2 mb-4">{{ session('message') }}</div>
        @endif
    
        <form wire:submit.prevent="placeOrder" class="space-y-4">
            <input type="text" wire:model="name" placeholder="Your Name" class="w-full p-2 border rounded">
            @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
    
            <input type="email" wire:model="email" placeholder="Your Email" class="w-full p-2 border rounded">
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
    
            <select wire:model="product_id" class="w-full p-2 border rounded">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</option>
                @endforeach
            </select>
            @error('product_id') <span class="text-red-500">{{ $message }}</span> @enderror
    
            <input type="number" wire:model="quantity" min="1" class="w-full p-2 border rounded">
            @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
    
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Place Order</button>
        </form>
    </div>
</div>
