<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
  
    public function changeQty(Request $request, $param, $id)
    {
       
        $cartId = (int)$id;
        $userLogin = auth()->user()->login;
        
        if ($param !== 'incr' && $param !== 'decr') {
            Log::error('Неверный параметр', ['param' => $param]);
            return response()->json(['error' => __('messages.cart.quantity_error')], 400);
        }

        Log::info('Изменение количества товара в корзине', [
            'param' => $param,
            'id' => $cartId,
            'user_login' => $userLogin
        ]);

        try {
            Log::info('Поиск товара в корзине', [
                'id' => $cartId,
                'user_login' => $userLogin
            ]);
            
            $cartItem = Cart::where('uid', $userLogin)
                ->where('id', $cartId)
                ->first();

            if (!$cartItem) {
                Log::error('Товар в корзине не найден', [
                    'id' => $cartId,
                    'user_login' => $userLogin
                ]);
                return response()->json(['error' => __('messages.cart.item_not_found')], 404);
            }
            
            Log::info('Товар в корзине найден', [
                'cart_id' => $cartItem->id,
                'product_id' => $cartItem->pid,
                'qty' => $cartItem->qty
            ]);

            Log::info('Поиск товара', [
                'product_id' => $cartItem->pid
            ]);
            
            $product = Product::find($cartItem->pid);

            if (!$product) {
                Log::error('Товар не найден', [
                    'product_id' => $cartItem->pid
                ]);
                $cartItem->delete();
                return response()->json(['error' => __('messages.product.not_found'), 'reload' => true], 404);
            }
            
            Log::info('Товар найден', [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'qty' => $product->qty
            ]);

          

            if ($param === 'incr') {
                Log::info('Попытка увеличить количество', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty,
                    'product_qty' => $product->qty
                ]);
                
                if ($cartItem->qty < $product->qty) {
                    $cartItem->update(['qty' => $cartItem->qty + 1]);
                    Log::info('Количество увеличено', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                }
                Log::warning('Достигнут лимит количества', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty,
                    'max_qty' => $product->qty
                ]);
                return response()->json(['error' => __('messages.cart.quantity_limit')], 400);
            }

            if ($param === 'decr') {
                Log::info('Попытка уменьшить количество', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty
                ]);
                
                if ($cartItem->qty > 1) {
                    $cartItem->update(['qty' => $cartItem->qty - 1]);
                    Log::info('Количество уменьшено', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                } else {
                    $cartItem->delete();
                    Log::info('Товар удален из корзины', [
                        'cart_id' => $cartItem->id
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Ошибка при изменении количества товара', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'param' => $param,
                'id' => $cartId,
                'user_login' => $userLogin
            ]);
            return response()->json(['error' => __('messages.cart.quantity_error')], 500);
        }
    }

   
    public function index(Request $request)
    {
     
        $cart = auth()->user()->cart;
        $cartItems = $cart->products;

        return view('cart', ['cart' => $cartItems]);
    }

 
    public function addToCart(Request $request)
    {
        try {
            $productId = (int)$request->id;
            $userLogin = $request->user()->login;
            
            \Log::info('Добавление в корзину', [
                'product_id' => $productId,
                'user_login' => $userLogin
            ]);

            $product = Product::find($productId);

            \Log::info('Товар найден', [
                'product' => $product ? [
                    'id' => $product->id,
                    'title' => $product->title,
                    'qty' => $product->qty
                ] : null
            ]);

            if (!$product) {
                \Log::error('Товар не найден', ['product_id' => $productId]);
                return response()->json(['error' => __('messages.product.not_found')], 404);
            }

          
            $itemInCart = Cart::where('uid', $userLogin)
                ->where('pid', $productId)
                ->first();

            \Log::info('Товар в корзине', [
                'exists' => $itemInCart ? true : false,
                'current_qty' => $itemInCart ? $itemInCart->qty : 0
            ]);

            if (!$itemInCart) {
                
                Cart::create([
                    'uid' => $userLogin,
                    'pid' => $productId,
                    'qty' => 1,
                ]);
                \Log::info('Новый товар добавлен в корзину');
                return response()->json(['success' => true, 'message' => __('messages.product.added_to_cart')]);
            }

            
            if ($product->qty > $itemInCart->qty) {
                $itemInCart->update(['qty' => $itemInCart->qty + 1]);
                \Log::info('Количество товара в корзине увеличено', [
                    'new_qty' => $itemInCart->qty + 1
                ]);
                return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
            }

            \Log::error('Достигнут лимит количества товара', [
                'product_qty' => $product->qty,
                'cart_qty' => $itemInCart->qty
            ]);
            return response()->json(['error' => __('messages.cart.quantity_limit')], 400);
        } catch (\Exception $e) {
            \Log::error('Ошибка при добавлении в корзину', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => __('messages.cart.quantity_error')], 500);
        }
    }
}
