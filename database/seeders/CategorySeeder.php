<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'product_type' => 'Цветы',
            ],
            [
                'id' => 2,
                'product_type' => 'Упаковка',
            ],
            [
                'id' => 3,
                'product_type' => 'Дополнительно',
            ],
        ]
        );
    }
}
