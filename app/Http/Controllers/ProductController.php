<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class
ProductController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id;
        $product = DB::table('products')
            ->join(
                'categories',
                'categories.id', '=', 'products.product_type'
            )->where('products.id', $id)->first();
        if (!is_null($product)) {
            $product->id = intval($id);
            return view('product', ['product' => $product]);
        } else {
            return view('404');
        }
    }

    public function getProducts(Request $request)
    {
        $products = DB::table('products')->join(
            'categories',
            'categories.id',
            '=',
            'products.product_type'
        )->select('products.id as id',
            'products.*',
            'categories.product_type as product_type'
        )->get();
        return view('admin.products', ['products' => $products]);
    }

    public function getProductById(Request $request)
    {
        $id = $request->id;
        $categories = DB::table('categories')->get();
        $product = DB::table('products')->join(
            'categories',
            'categories.id',
            '=',
            'products.product_type'
        )->select(
            'products.id as id',
            'products.*',
            'categories.product_type as product_type'
        )->where('products.id', $id)->first();

        if (!is_null($product)) {
            return view('admin.product-edit', ['categories' => $categories, 'product' => $product]);
        } else {
            return abort(404);
        }
    }


    public function editProduct(Request $request, $id)
    {
        $product = Product::find($id);
        
        $product->title = $request->input('title');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->qty = $request->input('qty');
        $product->color = $request->input('color');
        $product->img = $request->input('img');
        $product->country = $request->input('country');
        $product->product_type = $request->input('category');
        
        $product->save();
        
        return redirect('/products');
    }

    public function createProductView()
    {
        $categories = DB::table('categories')->get();
        return view('admin.product-create', ['categories' => $categories]);
    }

    public function createProduct(Request $request)
    {
        DB::table('products')->insert([
            'title' => $request->input('title'),
            'qty' => $request->input('qty'),
            'price' => $request->input('price'),
            'product_type' => $request->input('category'),
            'img' => $request->input('img'),
            'country' => $request->input('country'),
            'color' => $request->input('color'),
            'created_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);
        return redirect()->route('admin.products');
    }
    public function deleteProduct(Request $request)
    {
        $product = DB::table('products')->where('id', $request->id);
        $product->delete();
        return redirect()->route('admin.products');
    }

}
