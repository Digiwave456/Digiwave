<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $cart = Cart::where('uid', auth()->id())->get();
        $total = 0;

        // Проверяем, пуста ли корзина
        if ($cart->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Корзина пуста. Добавьте товары в корзину перед оформлением заказа.');
        }

        // Проверки наличия товаров теперь обрабатываются через наблюдатель
        // Наблюдатель автоматически удаляет недоступные товары из корзины

        foreach ($cart as $item) {
            $product = Product::find($item->pid);
            if ($product) {
                $total += $product->price * $item->qty;
            }
        }

        return view('createOrder', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        Log::info('Creating order', ['user_id' => auth()->id()]);

        try {
            $cart = Cart::where('uid', auth()->id())->get();
            
            if ($cart->isEmpty()) {
                Log::warning('Attempt to create order with empty cart', ['user_id' => auth()->id()]);
                return redirect()->route('cart')->with('error', 'Корзина пуста. Добавьте товары в корзину перед оформлением заказа.');
            }

            // Проверяем, все ли товары в корзине существуют
            $missingProducts = [];
            foreach ($cart as $item) {
                $product = Product::find($item->pid);
                if (!$product) {
                    $missingProducts[] = $item->id;
                    Log::error('Product not found', ['product_id' => $item->pid]);
                }
            }
            
            if (!empty($missingProducts)) {
                // Удаляем несуществующие товары из корзины
                Cart::whereIn('id', $missingProducts)->delete();
                return redirect()->route('cart')->with('error', 'Некоторые товары в корзине больше не доступны. Они были удалены из корзины.');
            }

            foreach ($cart as $item) {
                $product = Product::find($item->pid);
                
                // Проверка наличия товара теперь обрабатывается через наблюдатель
                // Если товара нет в наличии, наблюдатель автоматически удалит его из корзины

                $order = Order::create([
                    'uid' => auth()->id(),
                    'pid' => $item->pid,
                    'qty' => $item->qty,
                    'number' => uniqid(),
                    'status' => 'Новый'
                ]);

                Log::info('Order created', [
                    'order_id' => $order->id,
                    'product_id' => $item->pid,
                    'quantity' => $item->qty
                ]);

                $product->decrement('qty', $item->qty);
                $item->delete();
            }

            Log::info('Order process completed successfully', ['user_id' => auth()->id()]);
            return redirect()->route('user')->with('success', 'Заказ успешно создан');
        } catch (\Exception $e) {
            Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart')->with('error', 'Произошла ошибка при создании заказа: ' . $e->getMessage());
        }
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
