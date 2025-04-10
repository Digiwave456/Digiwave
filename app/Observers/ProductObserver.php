<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Log;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Если количество товара изменилось
        if ($product->isDirty('qty')) {
            $oldQty = $product->getOriginal('qty');
            $newQty = $product->qty;
            
            Log::info('Product quantity changed', [
                'product_id' => $product->id,
                'product_title' => $product->title,
                'old_qty' => $oldQty,
                'new_qty' => $newQty
            ]);
            
            // Если количество уменьшилось или товар закончился
            if ($newQty < $oldQty || $newQty <= 0) {
                // Находим все записи в корзине с этим товаром
                $cartItems = Cart::where('pid', $product->id)->get();
                
                Log::info('Found cart items for product', [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'cart_items_count' => $cartItems->count()
                ]);
                
                foreach ($cartItems as $cartItem) {
                    // Если количество в корзине больше, чем доступно
                    if ($cartItem->qty > $newQty) {
                        if ($newQty <= 0) {
                            // Если товара нет в наличии, удаляем из корзины
                            Log::info('Removing cart item due to product out of stock', [
                                'cart_id' => $cartItem->id,
                                'product_id' => $product->id,
                                'product_title' => $product->title,
                                'user_id' => $cartItem->uid
                            ]);
                            $cartItem->delete();
                        } else {
                            // Иначе уменьшаем количество в корзине до доступного
                            Log::info('Adjusting cart item quantity', [
                                'cart_id' => $cartItem->id,
                                'product_id' => $product->id,
                                'product_title' => $product->title,
                                'user_id' => $cartItem->uid,
                                'old_qty' => $cartItem->qty,
                                'new_qty' => $newQty
                            ]);
                            $cartItem->update(['qty' => $newQty]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        Log::info('Product deleted, removing from all carts', [
            'product_id' => $product->id,
            'product_title' => $product->title
        ]);
        
        // Удаляем все записи в корзине с этим товаром
        $deletedCount = Cart::where('pid', $product->id)->delete();
        
        Log::info('Removed product from carts', [
            'product_id' => $product->id,
            'product_title' => $product->title,
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
