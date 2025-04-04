<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all();
        return view('admin.categories', ['categories' => $categories]);
    }
    public function createCategoryView()
    {
        $categories = Category::all();
        return view('admin.category-create', ['categories' => $categories]);
    }
    public function createCategory(Request $request)
    {
        Category::create([
            'product_type' => $request->input('title'),
        ]);
        return redirect()->route('admin.categories');
    }
    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);
        
        if ($category) {
            // Delete related products and their cart/order items
            $products = Product::where('product_type', $category->id)->get();
            
            foreach ($products as $product) {
                Cart::where('pid', $product->id)->delete();
                Order::where('pid', $product->id)->delete();
                $product->delete();
            }
            
            $category->delete();
        }

        return redirect()->route('admin.categories');
    }
    public function editCategoryById(Request $request)
    {
        $category = Category::find($request->id);
        
        if (!$category) {
            return abort(404);
        }

        return view('admin.category-edit', ['category' => $category]);
    }
    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        
        if ($category) {
            $category->update([
                'product_type' => $request->input('title')
            ]);
        }
        
        return redirect()->route('admin.categories');
    }
}
