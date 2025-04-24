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
        
        Log::info('Changing cart item quantity', [
            'param' => $param,
            'id' => $cartId,
            'user_login' => $userLogin
        ]);

       
        if ($param !== 'incr' && $param !== 'decr') {
            Log::error('Invalid parameter', ['param' => $param]);
            return response()->json(['error' => __('messages.cart.quantity_error')], 400);
        }

        try {
            Log::info('Looking for cart item', [
                'id' => $cartId,
                'user_login' => $userLogin
            ]);
            
            $cartItem = Cart::where('uid', $userLogin)
                ->where('id', $cartId)
                ->first();

            if (!$cartItem) {
                Log::error('Cart item not found', [
                    'id' => $cartId,
                    'user_login' => $userLogin
                ]);
                return response()->json(['error' => __('messages.cart.item_not_found')], 404);
            }
            
            Log::info('Cart item found', [
                'cart_id' => $cartItem->id,
                'product_id' => $cartItem->pid,
                'qty' => $cartItem->qty
            ]);

            Log::info('Looking for product', [
                'product_id' => $cartItem->pid
            ]);
            
            $product = Product::find($cartItem->pid);

            if (!$product) {
                Log::error('Product not found', [
                    'product_id' => $cartItem->pid
                ]);
                $cartItem->delete();
                return response()->json(['error' => __('messages.product.not_found'), 'reload' => true], 404);
            }
            
            Log::info('Product found', [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'qty' => $product->qty
            ]);

          

            if ($param === 'incr') {
                Log::info('Attempting to increase quantity', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty,
                    'product_qty' => $product->qty
                ]);
                
                if ($cartItem->qty < $product->qty) {
                    $cartItem->update(['qty' => $cartItem->qty + 1]);
                    Log::info('Quantity increased', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                }
                Log::warning('Quantity limit reached', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty,
                    'max_qty' => $product->qty
                ]);
                return response()->json(['error' => __('messages.cart.quantity_limit')], 400);
            }

            if ($param === 'decr') {
                Log::info('Attempting to decrease quantity', [
                    'cart_id' => $cartItem->id,
                    'current_qty' => $cartItem->qty
                ]);
                
                if ($cartItem->qty > 1) {
                    $cartItem->update(['qty' => $cartItem->qty - 1]);
                    Log::info('Quantity decreased', [
                        'cart_id' => $cartItem->id,
                        'new_qty' => $cartItem->qty
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                } else {
                    $cartItem->delete();
                    Log::info('Cart item deleted', [
                        'cart_id' => $cartItem->id
                    ]);
                    return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error changing cart item quantity', [
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
     
        $cartItems = Cart::with('product')
            ->where('uid', $request->user()->login)
            ->get();

     
        $cartItems = $cartItems->filter(function ($cartItem) {
            if (!$cartItem->product) {
                $cartItem->delete();
                return false;
            }
            return true;
        });

        return view('cart', ['cart' => $cartItems]);
    }

 
    public function addToCart(Request $request)
    {
        try {
            $productId = (int)$request->id;
            $userLogin = $request->user()->login;
            
            \Log::info('Adding to cart', [
                'product_id' => $productId,
                'user_login' => $userLogin
            ]);

            $product = Product::find($productId);

            \Log::info('Product found', [
                'product' => $product ? [
                    'id' => $product->id,
                    'title' => $product->title,
                    'qty' => $product->qty
                ] : null
            ]);

            if (!$product) {
                \Log::error('Product not found', ['product_id' => $productId]);
                return response()->json(['error' => __('messages.product.not_found')], 404);
            }

          
            $itemInCart = Cart::where('uid', $userLogin)
                ->where('pid', $productId)
                ->first();

            \Log::info('Item in cart', [
                'exists' => $itemInCart ? true : false,
                'current_qty' => $itemInCart ? $itemInCart->qty : 0
            ]);

            if (!$itemInCart) {
                
                Cart::create([
                    'uid' => $userLogin,
                    'pid' => $productId,
                    'qty' => 1,
                ]);
                \Log::info('New cart item created');
                return response()->json(['success' => true, 'message' => __('messages.product.added_to_cart')]);
            }

            
            if ($product->qty > $itemInCart->qty) {
                $itemInCart->update(['qty' => $itemInCart->qty + 1]);
                \Log::info('Cart item quantity increased', [
                    'new_qty' => $itemInCart->qty + 1
                ]);
                return response()->json(['success' => true, 'message' => __('messages.cart.quantity_updated')]);
            }

            \Log::error('Product quantity limit reached', [
                'product_qty' => $product->qty,
                'cart_qty' => $itemInCart->qty
            ]);
            return response()->json(['error' => __('messages.cart.quantity_limit')], 400);
        } catch (\Exception $e) {
            \Log::error('Error adding to cart', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => __('messages.cart.quantity_error')], 500);
        }
    }
}
