<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Указываем, какие поля могут быть массово присвоены
    protected $fillable = [
        'title', 'price', 'img', 'product_type', 'country', 'color', 'qty', 'description',
    ];

    // Если необходимо, добавьте методы и связи
}
