<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $cartTable = DB::table('cart')->where('uid', $request->user()->id)->get();
        $goodCart = [];
        $total = 0;

        foreach ($cartTable as $cartItem) {
            $product = DB::table('products')->select('title', 'price', 'qty')->where('id', $cartItem->pid)->first();
            $total += $cartItem->qty * $product->price;

            $goodCart[] = (object)[
                'id' => $cartItem->id,
                'title' => $product->title,
                'price' => $product->price,
                'qty' => $cartItem->qty,
                'limit' => $product->qty,
            ];
        }

        return view('createOrder', ['cart' => $goodCart, 'total' => $total]);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (Hash::check($request->get('password'), $request->user()->password)) {
            $orderNumber = $this->generateOrderNumber();
            $userCartTable = DB::table('cart')->where('uid', $request->user()->id)->get();

            DB::transaction(function () use ($userCartTable, $orderNumber, $request) {
                foreach ($userCartTable as $cartItem) {
                    DB::table('orders')->insert([
                        'uid' => $cartItem->uid,
                        'pid' => $cartItem->pid,
                        'qty' => $cartItem->qty,
                        'number' => $orderNumber,
                        'created_at' => now(),
                        'status' => 'Новый',
                    ]);
                }

                DB::table('cart')->where('uid', $request->user()->id)->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Заказ успешно сформирован! Номер вашего заказа: ' . $orderNumber,
                'orderNumber' => $orderNumber
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверный пароль. Пожалуйста, проверьте правильность введенного пароля.'
            ], 403);
        }
    }

    private function generateOrderNumber()
    {
        do {
            $orderNumber = Str::random(8);
        } while (DB::table('orders')->where('number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function getOrders(Request $request)
    {
        $goodOrders = [];
        $filter = $request->query('filter');
        $ordersTable = DB::table('orders');

        if ($filter === 'new') {
            $ordersTable->where('status', 'Новый');
        } elseif ($filter === 'confirmed') {
            $ordersTable->where('status', 'Подтвержден');
        } elseif ($filter === 'canceled') {
            $ordersTable->where('status', 'Отменен');
        }

        $ordersTable = $ordersTable->get()->groupBy('number');

        foreach ($ordersTable as $orderGroup) {
            $openedOrder = $orderGroup->all();
            $user = DB::table('users')->where('id', $openedOrder[0]->uid)->first(['name', 'surname', 'patronymic']);
            $totalPrice = 0;
            $totalQty = 0;
            $products = [];

            foreach ($openedOrder as $orderItem) {
                $product = DB::table('products')->where('id', $orderItem->pid)->first();
                $totalPrice += $product->price * $orderItem->qty;
                $totalQty += $orderItem->qty;

                $products[] = (object)[
                    'title' => $product->title,
                    'price' => $product->price,
                    'qty' => $orderItem->qty,
                ];
            }

            $goodOrders[] = (object)[
                'name' => $user->surname . ' ' . $user->name . ' ' . $user->patronymic,
                'number' => $openedOrder[0]->number,
                'products' => $products,
                'date' => $openedOrder[0]->created_at,
                'totalPrice' => $totalPrice,
                'totalQty' => $totalQty,
                'status' => $openedOrder[0]->status,
            ];
        }

        return view('admin.orders', ['orders' => $goodOrders]);
    }

    public function editOrderStatus(Request $request, $action, $number)
    {
        if (!in_array($action, ['confirm', 'cancel'])) {
            return abort(400, 'Invalid action');
        }

        $order = DB::table('orders')->where('number', $number);

        if (!$order->exists()) {
            return abort(404, 'Order not found');
        }

        $status = $action === 'confirm' ? 'Подтвержден' : 'Отменен';
        $order->update(['status' => $status]);

        return redirect()->route('admin.orders')->with('success', 'Статус заказа успешно обновлен');
    }

    public function deleteOrder($number)
    {
        $order = DB::table('orders')->where('number', $number);
        
        if (!$order->exists()) {
            return abort(404, 'Заказ не найден');
        }

        // Проверяем, что заказ в статусе "Новый"
        $orderStatus = $order->first()->status;
        if ($orderStatus !== 'Новый') {
            return back()->with('error', 'Можно удалять только новые заказы');
        }

        // Удаляем заказ
        $order->delete();

        return redirect('/user')->with('success', 'Заказ успешно удален');
    }
}
