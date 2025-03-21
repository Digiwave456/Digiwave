<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // Получаем 4 последних добавленных товара
        $newProducts = Product::orderBy('created_at', 'desc')
                             ->take(4)
                             ->get();
        
        return view('welcome', compact('products', 'newProducts'));
    }
} 