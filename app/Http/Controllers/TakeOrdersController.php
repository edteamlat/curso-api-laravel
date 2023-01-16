<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;

class TakeOrdersController extends Controller
{
    public function update(Order $order)
    {
        abort_unless(Auth::user()->isDelivery(), 403, "You don't have the role 'Delivery'.");
        abort_unless($order->isPending(), 403, "This order was already taken.");

        $order->delivery_user_id = Auth::id();
        $order->status = 'delivery assigned';
        $order->save();

        return new OrderResource($order);
    }
}
