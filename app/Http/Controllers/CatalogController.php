<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function getProducts(Request $request)
    {
        $query = Product::where('qty', '>', 0);
        $categories = Category::all();
        $params = collect($request->query());

        if ($params->get('sort_by')) {
            $query->orderBy($params->get('sort_by'));
        }
        if ($params->get('sort_by_desc')) {
            $query->orderByDesc($params->get('sort_by_desc'));
        }
        if ($params->get('filter')) {
            $query->where('product_type', $params->get('filter'));
        }

        $products = $query->get();

        return view('catalog', [
            'products' => $products,
            'categories' => $categories,
            'params' => $params
        ]);
    }
}
