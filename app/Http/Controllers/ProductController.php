<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $product = Product::with('category')
            ->find($request->id);

        if (!$product) {
            return view('404');
        }

        return view('product', ['product' => $product]);
    }

    public function getProducts(Request $request)
    {
        $products = Product::with('category')
            ->select('products.*', 'categories.product_type')
            ->join('categories', 'categories.id', '=', 'products.product_type')
            ->get();

        return view('admin.products', ['products' => $products]);
    }

    public function getProductById(Request $request)
    {
        $product = Product::with('category')
            ->select('products.*', 'categories.product_type')
            ->join('categories', 'categories.id', '=', 'products.product_type')
            ->find($request->id);

        if (!$product) {
            return abort(404);
        }

        $categories = Category::all();
        return view('admin.product-edit', ['categories' => $categories, 'product' => $product]);
    }

    public function editProduct(Request $request, $id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            return redirect('/products');
        }

        $product->update([
            'title' => $request->input('title'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'qty' => $request->input('qty'),
            'color' => $request->input('color'),
            'img' => $request->input('img'),
            'country' => $request->input('country'),
            'product_type' => $request->input('category'),
        ]);
        
        return redirect('/products');
    }

    public function createProductView()
    {
        $categories = Category::all();
        return view('admin.product-create', ['categories' => $categories]);
    }

    public function createProduct(Request $request)
    {
        Product::create([
            'title' => $request->input('title'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'product_type' => $request->input('category'),
            'img' => $request->input('img'),
            'country' => $request->input('country'),
            'color' => $request->input('color'),
        ]);

        return redirect()->route('admin.products');
    }

    public function deleteProduct(Request $request)
    {
        $product = Product::find($request->id);
        
        if ($product) {
            $product->delete();
        }

        return redirect()->route('admin.products');
    }
}
