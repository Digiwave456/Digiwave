<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    // Изменение количества товара в корзине
    public function changeQty(Request $request, $param, $id)
    {
        Log::info('Changing cart item quantity', [
            'param' => $param,
            'id' => $id,
            'user_id' => auth()->id()
        ]);

        try {
            $cartItem = Cart::where('uid', auth()->id())
                ->where('id', $id)
                ->first();

            if (!$cartItem) {
                Log::error('Cart item not found', [
                    'id' => $id,
                    'user_id' => auth()->id()
                ]);
                return response()->json(['error' => 'Товар в корзине не найден'], 404);
            }

            $product = Product::find($cartItem->pid);

            if (!$product) {
                Log::error('Product not found', [
                    'product_id' => $cartItem->pid
                ]);
                $cartItem->delete();
                return response()->json(['error' => 'Товар больше не доступен', 'reload' => true], 404);
            }

            if ($product->qty <= 0) {
                Log::error('Product out of stock', [
                    'product_id' => $product->id,
                    'qty' => $product->qty
                ]);
                $cartItem->delete();
                return response()->json(['error' => 'Товара нет в наличии', 'reload' => true], 400);
            }

            if ($param === 'incr') {
                if ($cartItem->qty < $product->qty) {
                    $cartItem->update(['qty' => $cartItem->qty + 1]);
                    Log::info('Quantity increased', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                    return response()->json(['success' => true]);
                }
                Log::warning('Quantity limit reached', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty,
                    'max_qty' => $product->qty
                ]);
                return response()->json(['error' => 'Достигнут лимит доступного количества товара'], 400);
            }

            if ($param === 'decr') {
                if ($cartItem->qty > 1) {
                    $cartItem->update(['qty' => $cartItem->qty - 1]);
                    Log::info('Quantity decreased', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                } else {
                    $cartItem->delete();
                    Log::info('Cart item deleted', [
                        'cart_id' => $cartItem->id
                    ]);
                }
                return response()->json(['success' => true]);
            }

            Log::error('Invalid parameter', ['param' => $param]);
            return response()->json(['error' => 'Неверный параметр'], 400);
        } catch (\Exception $e) {
            Log::error('Error changing cart item quantity', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Произошла ошибка при изменении количества'], 500);
        }
    }

    // Отображение корзины
    public function index(Request $request)
    {
        $cartItems = Cart::with('product')
            ->where('uid', $request->user()->id)
            ->get();

        // Проверяем наличие всех товаров и удаляем недоступные
        foreach ($cartItems as $cartItem) {
            if (!$cartItem->product || $cartItem->product->qty <= 0) {
                $cartItem->delete();
            } elseif ($cartItem->qty > $cartItem->product->qty) {
                $cartItem->update(['qty' => $cartItem->product->qty]);
            }
        }

        // Получаем обновленный список товаров
        $cartItems = Cart::with('product')
            ->where('uid', $request->user()->id)
            ->get();

        $goodCart = $cartItems->map(function ($cartItem) {
            return (object)[
                'id' => $cartItem->id,
                'title' => $cartItem->product->title ?? 'Неизвестный товар',
                'price' => $cartItem->product->price ?? 0,
                'qty' => $cartItem->qty,
                'limit' => $cartItem->product->qty ?? 0,
                'img' => $cartItem->product->img ?? '',
            ];
        });

        return view('cart', ['cart' => $goodCart]);
    }

    // Добавление товара в корзину
    public function addToCart(Request $request)
    {
        try {
            \Log::info('Adding to cart', [
                'product_id' => $request->id,
                'user_id' => $request->user()->id
            ]);

            $product = Product::find($request->id);

            \Log::info('Product found', [
                'product' => $product ? [
                    'id' => $product->id,
                    'title' => $product->title,
                    'qty' => $product->qty
                ] : null
            ]);

            if (!$product) {
                \Log::error('Product not found', ['product_id' => $request->id]);
                return response()->json(['error' => 'Товар не найден'], 404);
            }

            // Проверяем наличие товара
            if ($product->qty <= 0) {
                \Log::error('Product out of stock', [
                    'product_id' => $product->id,
                    'qty' => $product->qty
                ]);
                return response()->json(['error' => 'Товара нет в наличии'], 400);
            }

            // Проверяем, есть ли товар уже в корзине
            $itemInCart = Cart::where('uid', $request->user()->id)
                ->where('pid', $request->id)
                ->first();

            \Log::info('Item in cart', [
                'exists' => $itemInCart ? true : false,
                'current_qty' => $itemInCart ? $itemInCart->qty : 0
            ]);

            if (!$itemInCart) {
                // Создаем новую запись в корзине
                Cart::create([
                    'uid' => $request->user()->id,
                    'pid' => $request->id,
                    'qty' => 1,
                ]);
                \Log::info('New cart item created');
                return response()->json(['success' => 'Товар добавлен в корзину']);
            }

            // Если товар уже в корзине, проверяем лимит
            if ($product->qty > $itemInCart->qty) {
                $itemInCart->update(['qty' => $itemInCart->qty + 1]);
                \Log::info('Cart item quantity increased', [
                    'new_qty' => $itemInCart->qty + 1
                ]);
                return response()->json(['success' => 'Количество товара увеличено']);
            }

            \Log::error('Product quantity limit reached', [
                'product_qty' => $product->qty,
                'cart_qty' => $itemInCart->qty
            ]);
            return response()->json(['error' => 'Достигнут лимит доступного количества товара'], 400);
        } catch (\Exception $e) {
            \Log::error('Error adding to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Произошла ошибка при добавлении товара в корзину'], 500);
        }
    }
}
