<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        
        return [
            'uid' => User::factory(),
            'pid' => $product->id,
            'qty' => fake()->numberBetween(1, 5),
        ];
    }
} 