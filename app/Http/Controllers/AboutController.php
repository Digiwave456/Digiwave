<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class AboutController extends Controller
{
    public function index()
    {
        $products = DB::table("products")->select(['id','img','title'])->get()->sortByDesc("created_at")->take(5);
        $newProducts = Product::orderBy('created_at', 'desc')
                             ->take(4)
                             ->get();
        
        return view('welcome', [
            'products' => $products,
            'newProducts' => $newProducts
        ]);
    }
}
