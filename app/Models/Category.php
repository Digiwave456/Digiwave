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

   
    public function deleteWithRelations()
    {
        return DB::transaction(function () {
          
            $productIds = $this->products()->pluck('id')->toArray();
            
            if (!empty($productIds)) {
                
                Cart::whereIn('pid', $productIds)->delete();
                
               
                Product::whereIn('id', $productIds)->delete();
            }
            
           
            return $this->delete();
        });
    }
} 