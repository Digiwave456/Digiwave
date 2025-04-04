<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

   
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'price' => fake()->numberBetween(100, 10000),
            'img' => fake()->imageUrl(640, 480, 'product'),
            'product_type' => Category::factory(),
            'country' => fake()->country(),
            'color' => fake()->colorName(),
            'qty' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
        ];
    }
} 