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
        return [
            'uid' => User::factory(),
            'pid' => Product::factory(),
            'qty' => fake()->numberBetween(1, 5),
        ];
    }
} 