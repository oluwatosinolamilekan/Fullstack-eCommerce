<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $brands = [
        //     'Nike', 'Adidas', 'Apple', 'Samsung', 'Sony', 'Microsoft',
        //     'Tesla', 'Coca-Cola', 'Pepsi', 'Amazon', 'Google', 'Facebook',
        // ];


        // return [
        //     'name' => fake()->randomElement($brands),
        //     'parent_id' => $this->faker->optional(0.5)->randomElement(Category::pluck('id')->toArray()), // 50% chance of having a parent category
        // ];
        // A list of real brands for category names
        $brands = [
            'Nike',
            'Adidas',
            'Samsung',
            'Apple',
            'Sony',
            'Canon',
            'Microsoft',
            'LG',
            'Honda',
            'Toyota'
        ];

        // Randomly select a brand for the category name
        $brandName = $this->faker->randomElement($brands);

        return [
            'name' => $brandName,
            'parent_id' => null, // Set to null for top-level categories (parents)
        ];
    }

 /**
     * Create child categories (subcategories).
     */
    public function withChildren()
    {
        return $this->afterCreating(function (Category $category) {
            // Create a few child categories for the parent category
            Category::factory()->count(3)->create([
                'parent_id' => $category->id, // Set the parent_id for child categories
            ]);
        });
    }
}
