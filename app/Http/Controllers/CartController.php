<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Изменение количества товара в корзине
    public function changeQty(Request $request)
    {
        $cartItem = DB::table('cart')
            ->where('uid', $request->user()->id)
            ->where('id', $request->id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Товар в корзине не найден'], 404);
        }

        $product = DB::table('products')->where('id', $cartItem->pid)->first();

        if (!$product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        if ($request->param === 'incr') {
            if ($cartItem->qty < $product->qty) {
                DB::table('cart')
                    ->where('id', $request->id)
                    ->update(['qty' => $cartItem->qty + 1]);
                return response()->json(['success' => true]);
            }
            return response()->json(['error' => 'Достигнут лимит доступного количества товара'], 400);
        }

        if ($request->param === 'decr') {
            if ($cartItem->qty > 1) {
                DB::table('cart')
                    ->where('id', $request->id)
                    ->update(['qty' => $cartItem->qty - 1]);
            } else {
                DB::table('cart')->where('id', $request->id)->delete();
            }
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Неверный параметр'], 400);
    }

    // Отображение корзины
    public function index(Request $request)
    {
        $cartItems = DB::table('cart')
            ->where('uid', $request->user()->id)
            ->get();

        $goodCart = $cartItems->map(function ($cartItem) {
            $product = DB::table('products')
                ->select('title', 'price', 'qty','img')
                ->where('id', $cartItem->pid)
                ->first();

            return (object)[
                'id' => $cartItem->id,
                'title' => $product->title ?? 'Неизвестный товар',
                'price' => $product->price ?? 0,
                'qty' => $cartItem->qty,
                'limit' => $product->qty ?? 0,
                'img' =>$product->img,
            ];
        });

        return view('cart', ['cart' => $goodCart]);
    }

    // Добавление товара в корзину
    public function addToCart(Request $request)
    {
        $product = DB::table('products')->where('id', $request->id)->first();

        if (!$product) {
            return response()->json(['error' => 'Товар не найден'], 404);
        }

        $cartTable = DB::table('cart');
        $itemInCart = $cartTable
            ->where('uid', $request->user()->id)
            ->where('pid', $request->id)
            ->first();

        if (!$itemInCart) {
            $cartTable->insert([
                'uid' => $request->user()->id,
                'pid' => $request->id,
                'qty' => 1,
            ]);
            return response()->json(['success' => 'Товар добавлен в корзину']);
        }

        if ($product->qty > $itemInCart->qty) {
            $cartTable
                ->where('uid', $request->user()->id)
                ->where('pid', $request->id)
                ->update(['qty' => $itemInCart->qty + 1]);
            return response()->json(['success' => 'Количество товара увеличено']);
        }

        return response()->json(['error' => 'Достигнут лимит доступного количества товара'], 400);
    }
}
