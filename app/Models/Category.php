<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_type');
    }

    /**
     * Оптимизированное удаление категории и связанных записей
     */
    public function deleteWithRelations()
    {
        return DB::transaction(function () {
            // Получаем все ID продуктов этой категории
            $productIds = $this->products()->pluck('id')->toArray();
            
            if (!empty($productIds)) {
                // Удаляем записи из корзины одним запросом
                Cart::whereIn('pid', $productIds)->delete();
                
                // Удаляем все продукты одной категории одним запросом
                Product::whereIn('id', $productIds)->delete();
            }
            
            // Удаляем саму категорию
            return $this->delete();
        });
    }
} 