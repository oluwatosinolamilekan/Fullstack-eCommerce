<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use App\Enums\UserEnums;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role_id' => UserEnums::ADMIN->value, // Use ->value to get the integer
        ]);

        // seed 10 users to be a customer..
        User::factory(10)->create();

        // Create 10 categories
        // $categories = Category::factory(10)->create();
           // Create categories with children
        Category::factory()->count(5) // Create 5 parent categories
           ->withChildren() // Add children to each parent category
           ->create();

        $categories = Category::all();

         // Create 50 products and attach random categories
        Product::factory(500)->create()->each(function ($product) use ($categories) {
            $product->categories()->attach($categories->random(rand(1, 3))->pluck('id'));
        });
 
       Order::factory()
       ->count(20)
        ->has(OrderItem::factory()->count(3), 'items') // Use 'items' instead of 'orderItem'
        ->create();
    }
}
