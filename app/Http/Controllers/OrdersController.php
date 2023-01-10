<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\CartItem;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    function store()
    {
        abort_unless(Auth::user()->tokenCan('orders:create'), 403, "You don't have permissions to perform this action.");

        Cart::restore(Auth::user()->email);

        $content = Cart::content()->map(function (CartItem $cartItem) {
            return [
                'name' => $cartItem->name,
                'price' => $cartItem->price,
                'qty' => $cartItem->qty,
                'tax_rate' => $cartItem->taxRate,
                'total' => $cartItem->total(),
            ];
        })->values();

        Cart::destroy();

        $order = Order::create([
            'user_id' => Auth::id(),
            'content' => $content,
        ]);

        return new OrderResource($order);
    }
}
