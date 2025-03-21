<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::table('products')->insert([
          [
              'title' => "Роза",
              'price' => 100,
              'img' => 'roza.jpg',
              'product_type' => 1,
              'country' => 'Голандия',
              'color' => 'Розовый',
              'qty' => 200,
              'created_at' => date('2024-02-19')
          ],
          [
              'title' => "Ликорис",
              'price' => 150,
              'img' => '4c32eb0bfcd94a4da663c3853efc9a22.jpg',
              'product_type' => 1,
              'country' => 'Голандия',
              'color' => 'Черный',
              'qty' => 100,
              'created_at' => date('2024-02-08')
          ],
          [
              'title' => "Сенполия (фиолетовая)",
              'price' => 200,
              'img' => 'imagetools7-3.jpg',
              'product_type' => 1,
              'country' => 'Голандия',
              'color' => 'Фиолетовый',
              'qty' => 150,
              'created_at' => date('2024-07-02')
          ],
          [
              'title' => "Хризантема Элен Вайт",
              'price' => 120,
              'img' => 'belye-cvety1.jpg',
              'product_type' => 1,
              'country' => 'Голандия',
              'color' => 'Белый',
              'qty' => 80,
              'created_at' => date('2024-09-09')
          ],
          [
              'title' => "Роза Блэк Баккара Чайно",
              'price' => 250,
              'img' => 'images_cms-image-000036756.jpg',
              'product_type' => 1,
              'country' => 'Голандия',
              'color' => 'Темно-красный',
              'qty' => 50,
              'created_at' => date('2024-10-01')
          ],
          [
              'title' => "Прозрачная ваза",
              'price' => 300,
              'img' => '225071.950x0.jpg',
              'product_type' => 3,
              'country' => 'Китай',
              'color' => 'Прозрачный',
              'qty' => 150,
              'created_at' => date('2024-04-23')
          ],
          [
              'title' => "Упаковка для цветов (в сетку)",
              'price' => 50,
              'img' => '155_setka_dlya_cvetov_pautina.jpg',
              'product_type' => 2,
              'country' => 'Россия',
              'color' => 'Разноцветный',
              'qty' => 300,
              'created_at' => date('2024-04-19')
          ]
      ]);
    }
}
