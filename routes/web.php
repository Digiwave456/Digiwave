<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Здесь вы можете зарегистрировать веб-маршруты для вашего приложения.
| Эти маршруты загружаются через RouteServiceProvider, и все они
| будут принадлежать группе посредников "web".
|
*/

// Маршруты для страниц
Route::get('/', [AboutController::class, 'index'])->name('about');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/catalog', [CatalogController::class, 'getProducts'])->name('catalog');
Route::get('/product/{id}', [ProductController::class, 'index'])->name('product');
Route::get('/where', function () {
    return view('where');
})->name('where');

// Маршруты для администратора
Route::middleware(['auth', 'is-admin'])->group(function () {
    // Маршруты для продуктов
    Route::get('/products', [ProductController::class, 'getProducts'])->name('admin.products');
    Route::get('/product-create', [ProductController::class, 'createProductView']);
    Route::post('/product-create', [ProductController::class, 'createProduct']);
    Route::get('/product-edit/{id}', [ProductController::class, 'getProductById']);
    Route::patch('/product-update/{id}', [ProductController::class, 'editProduct']);
    Route::delete('/product-delete/{id}', [ProductController::class, 'deleteProduct']);

    // Маршруты для категорий
    Route::get('/categories', [CategoriesController::class, 'getCategories'])->name('admin.categories');
    Route::get('/category-create', [CategoriesController::class, 'createCategoryView']);
    Route::post('/category-create', [CategoriesController::class, 'createCategory']);
    Route::get('/category-edit/{id}', [CategoriesController::class, 'editCategoryById']);
    Route::patch('/category-update/{id}', [CategoriesController::class, 'updateCategory']);
    Route::delete('/category-delete/{id}', [CategoriesController::class, 'deleteCategory']);

    // Маршруты для заказов
    Route::get('/orders', [OrderController::class, 'getOrders'])->name('admin.orders');
    Route::get('/order-status/{action}/{number}', [OrderController::class, 'editOrderStatus']);
    Route::patch('/order-status/{action}/{number}', [OrderController::class, 'editOrderStatus']);
});

// Маршруты для пользователей
Route::middleware('auth')->group(function () {
    // Профиль пользователя
    Route::get('/profile', [ProfileController::class, 'index'])->name('user'); // Страница профиля
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // Страница редактирования
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Обновление профиля
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // Удаление профиля
    Route::get('/user', [ProfileController::class, 'index'])->name('user');


    // Корзина
    Route::get('/add-to-cart/{id}', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/changeqty/{param}/{id}', [CartController::class, 'changeQty'])->name('changeQty');
    
    // Заказы
    Route::get('/create-order', [OrderController::class, 'index'])->name('create-order');
    Route::post('/create-order', [OrderController::class, 'createOrder']);
    Route::delete('/order-delete/{number}', [OrderController::class, 'deleteOrder'])->name('order.delete');
});

// Аутентификация
require __DIR__.'/auth.php';
