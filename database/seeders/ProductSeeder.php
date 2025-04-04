<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
   
    public function run(): void
    {
       
        $categories = Category::factory()->count(5)->create();

        
        Product::factory()
            ->count(20)
            ->create()
            ->each(function ($product) use ($categories) {
                $product->product_type = $categories->random()->id;
                $product->save();
            });
    }
}
