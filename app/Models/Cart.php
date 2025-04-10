<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';
    public $timestamps = false;

    protected $fillable = [
        'uid',
        'pid',
        'qty'
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'login');
    }

    
    public function product()
    {
        return $this->belongsTo(Product::class, 'pid');
    }
} 