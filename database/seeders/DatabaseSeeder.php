<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create 10 categories
         $categories = Category::factory(10)->create();

         // Create 50 products and attach random categories
         Product::factory(50)->create()->each(function ($product) use ($categories) {
             $product->categories()->attach($categories->random(rand(1, 3))->pluck('id'));
         });
 
         // Create 20 orders
        //  Order::factory(20)->create();
    }
}
