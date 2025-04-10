<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $category = Category::find($request->id);
            
            if ($category) {
                Log::info('Deleting category and related records', [
                    'category_id' => $category->id,
                    'category_name' => $category->product_type
                ]);
                
                $category->deleteWithRelations();
                
                Log::info('Category and related records deleted successfully', [
                    'category_id' => $category->id
                ]);
            }

            return redirect()->route('admin.categories');
        } catch (\Exception $e) {
            Log::error('Error deleting category', [
                'category_id' => $request->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.categories')
                ->with('error', 'Произошла ошибка при удалении категории');
        }
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
