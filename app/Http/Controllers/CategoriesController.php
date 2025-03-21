<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    public function getCategories()
    {
        $categories = DB::table('categories')->get();
        return view('admin.categories', ['categories' => $categories]);
    }
    public function createCategoryView()
    {
        $categories = DB::table('categories')->get();
        return view('admin.category-create', ['categories' => $categories]);
    }
    public function createCategory(Request $request)
    {
        DB::table('categories')->insert([
            'product_type' => $request->input('title'),
        ]);
        return redirect()->route('admin.categories');
    }
    public function deleteCategory(Request $request)
    {
        $category = DB::table('categories')->where('id', $request->id);
        $productsForDelete = DB::table('products')->where('product_type', $request->id);
        foreach ($productsForDelete->select('id')->get() as $value) {
            DB::table('cart')->where('pid', $value->id)->delete();
            DB::table('orders')->where('pid', $value->id)->delete();
        }
        $productsForDelete->delete();
        $category->delete();

        return redirect()->route('admin.categories');
    }
    public function editCategoryById(Request $request)
    {
        $id = $request->id;
        $category = DB::table('categories')->where('id', $id)->first();
        
        if (!is_null($category)) {
            return view('admin.category-edit', ['category' => $category]);
        } else {
            return abort(404);
        }
    }
    public function updateCategory(Request $request, $id)
    {
        DB::table('categories')
            ->where('id', $id)
            ->update([
                'product_type' => $request->input('title')
            ]);
        
        return redirect()->route('admin.categories');
    }
}
