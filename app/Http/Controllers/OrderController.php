<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $cart = Cart::where('uid', auth()->user()->login)->get();
        $total = 0;

       
        if ($cart->isEmpty()) {
            return redirect()->route('cart')->with('error', __('messages.cart.empty'));
        }

       
        $productIds = $cart->pluck('pid')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

      

        foreach ($cart as $item) {
            if (isset($products[$item->pid])) {
                $total += $products[$item->pid]->price * $item->qty;
            }
        }

        return view('createOrder', compact('cart', 'total', 'products'));
    }

    public function store(Request $request)
    {
        Log::info('Creating order', ['user_id' => auth()->user()->login]);

        try {
            $cart = Cart::where('uid', auth()->user()->login)->get();
            
            if ($cart->isEmpty()) {
                Log::warning('Attempt to create order with empty cart', ['user_id' => auth()->user()->login]);
                return redirect()->route('cart')->with('error', __('messages.cart.empty'));
            }

          
            $productIds = $cart->pluck('pid')->toArray();
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        
            $missingProducts = [];
            foreach ($cart as $item) {
                if (!isset($products[$item->pid])) {
                    $missingProducts[] = $item->id;
                    Log::error('Product not found', ['product_id' => $item->pid]);
                }
            }
            
            if (!empty($missingProducts)) {
                
                Cart::whereIn('id', $missingProducts)->delete();
                return redirect()->route('cart')->with('error', __('messages.product.not_found'));
            }

            foreach ($cart as $item) {
                $product = $products[$item->pid];
                
            

                $order = Order::create([
                    'uid' => auth()->user()->login,
                    'pid' => $item->pid,
                    'qty' => $item->qty,
                    'number' => uniqid(),
                    'status' => __('messages.order.statuses.pending')
                ]);

                Log::info('Order created', [
                    'order_id' => $order->id,
                    'product_id' => $item->pid,
                    'quantity' => $item->qty
                ]);

                $product->decrement('qty', $item->qty);
                $item->delete();
            }

            Log::info('Order process completed successfully', ['user_id' => auth()->user()->login]);
            return redirect()->route('user')->with('success', __('messages.success'));
        } catch (\Exception $e) {
            Log::error('Error creating order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart')->with('error', __('messages.error'));
        }
    }

    public function getOrders(Request $request)
    {
        $goodOrders = [];
        $filter = $request->query('filter');
        $ordersTable = DB::table('orders');

        if ($filter === 'new') {
            $ordersTable->where('status', __('messages.order.statuses.pending'));
        } elseif ($filter === 'confirmed') {
            $ordersTable->where('status', __('messages.order.statuses.completed'));
        } elseif ($filter === 'canceled') {
            $ordersTable->where('status', __('messages.order.statuses.cancelled'));
        }

        $ordersTable = $ordersTable->get()->groupBy('number');


        $productIds = collect($ordersTable)->flatten()->pluck('pid')->unique()->toArray();
        $allProducts = DB::table('products')->whereIn('id', $productIds)->get()->keyBy('id');

        
        $userLogins = collect($ordersTable)->flatten()->pluck('uid')->unique()->toArray();
        $users = DB::table('users')->whereIn('login', $userLogins)->get()->keyBy('login');

        foreach ($ordersTable as $orderGroup) {
            $openedOrder = $orderGroup->all();
            $user = $users[$openedOrder[0]->uid] ?? null;
            $totalPrice = 0;
            $totalQty = 0;
            $orderProducts = [];

            foreach ($openedOrder as $orderItem) {
                $product = $allProducts[$orderItem->pid] ?? null;
                if ($product) {
                    $totalPrice += $product->price * $orderItem->qty;
                    $totalQty += $orderItem->qty;

                    $orderProducts[] = (object)[
                        'title' => $product->title,
                        'price' => $product->price,
                        'qty' => $orderItem->qty,
                    ];
                }
            }

            if ($user) {
                $goodOrders[] = (object)[
                    'name' => $user->surname . ' ' . $user->name . ' ' . $user->patronymic,
                    'number' => $openedOrder[0]->number,
                    'products' => $orderProducts,
                    'date' => $openedOrder[0]->created_at,
                    'totalPrice' => $totalPrice,
                    'totalQty' => $totalQty,
                    'status' => $openedOrder[0]->status,
                ];
            }
        }

        return view('admin.orders', ['orders' => $goodOrders]);
    }

    public function editOrderStatus(Request $request, $action, $number)
    {
        if (!in_array($action, ['confirm', 'cancel'])) {
            return abort(400, __('messages.error'));
        }

        $order = DB::table('orders')->where('number', $number);

        if (!$order->exists()) {
            return abort(404, __('messages.order.not_found'));
        }

        $status = $action === 'confirm' ? __('messages.order.statuses.completed') : __('messages.order.statuses.cancelled');
        $order->update(['status' => $status]);

        return redirect()->route('admin.orders')->with('success', __('messages.success'));
    }

    public function deleteOrder($number)
    {
        $order = DB::table('orders')->where('number', $number);
        
        if (!$order->exists()) {
            return abort(404, __('messages.order.not_found'));
        }

     
        $orderStatus = $order->first()->status;
        if ($orderStatus !== __('messages.order.statuses.pending')) {
            return back()->with('error', __('messages.order.error'));
        }

       
        $order->delete();

        return redirect('/user')->with('success', __('messages.success'));
    }
}
