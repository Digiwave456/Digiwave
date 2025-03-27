<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

   
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'price' => fake()->numberBetween(100, 10000),
            'img' => fake()->imageUrl(640, 480, 'product'),
            'product_type' => fake()->numberBetween(1, 5), // Assuming you have categories with IDs 1-5
            'country' => fake()->country(),
            'color' => fake()->colorName(),
            'qty' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
        ];
    }
} 