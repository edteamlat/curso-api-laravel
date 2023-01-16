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
        $orders = Order::when(Auth::user()->isClient(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->when(Auth::user()->isDelivery(), function ($query) {
                $query->where('status', 'pending');
            })->paginate(10);

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
                'product_id' => $cartItem->options->product_id,
            ];
        })->values();

        Cart::destroy();

        $order = Order::create([
            'user_id' => Auth::id(),
            'content' => $content,
            'status' => 'pending',
        ]);

        return new OrderResource($order);
    }
}
