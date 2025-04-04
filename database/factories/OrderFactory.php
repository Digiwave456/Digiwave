<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'uid' => User::factory(),
            'pid' => Product::factory(),
            'qty' => fake()->numberBetween(1, 5),
            'number' => Str::random(8),
            'status' => fake()->randomElement(['Новый', 'Подтвержден', 'Отменен']),
        ];
    }
} 