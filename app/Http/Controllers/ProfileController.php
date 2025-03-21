<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request)
    {
        $goodOrders = [];
        $ordersTable = DB::table('orders')->where('uid', $request->user()->id)->get()->groupBy(['number']);

        foreach ($ordersTable as $order) {
            $openedOrder = $order->all();
            $date = $openedOrder[0]->created_at;
            $orderNumber = $openedOrder[0]->number;
            $orderStatus = $openedOrder[0]->status;
            $totalPrice = 0;
            $totalQty = 0;
            $products = [];

            foreach ($openedOrder as $orderItem) {
                $product = DB::table('products')->where('id', $orderItem->pid)->first();
                $totalPrice += $product->price * $orderItem->qty;
                $totalQty += $orderItem->qty;

                array_push($products,
                    (object)[
                        'title' => $product->title,
                        'price' => $product->price,
                        'qty' => $orderItem->qty,
                    ]);
            }
            array_push($goodOrders,
                (object)[
                    'number' => $orderNumber,
                    'products' => $products,
                    'date' => $date,
                    'totalPrice' => $totalPrice,
                    'totalQty' => $totalQty,
                    'status' => $orderStatus,

                ]);
        }
        return view('profile.index', ['orders' => $goodOrders]);
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
