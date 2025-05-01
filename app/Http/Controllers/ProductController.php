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
            return abort(404, __('messages.product.not_found'));
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
            return abort(404, __('messages.product.not_found'));
        }

        $categories = Category::all();
        return view('admin.product-edit', ['categories' => $categories, 'product' => $product]);
    }

    public function editProduct(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return abort(404, __('messages.product.not_found'));
        }

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'qty' => $request->qty,
            'product_type' => $request->product_type
        ]);

        return redirect()->route('admin.products')->with('success', __('messages.success'));
    }

    public function createProductView()
    {
        $categories = Category::all();
        return view('admin.product-create', ['categories' => $categories]);
    }

    public function createProduct(Request $request)
    {
        Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'qty' => $request->qty,
            'product_type' => $request->product_type
        ]);

        return redirect()->route('admin.products')->with('success', __('messages.success'));
    }

    public function deleteProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return abort(404, __('messages.product.not_found'));
        }

        $product->delete();

        return redirect()->route('admin.products')->with('success', __('messages.success'));
    }
}
