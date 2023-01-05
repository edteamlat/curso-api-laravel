<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::middleware('auth:sanctum')->post('orders', function () {
    abort_unless(Auth::user()->tokenCan('orders:create'), 403, "You don't have permissions to perform this action.");

    return [
        'message' => 'Order created',
    ];
});

Route::post('login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return Auth::user();
    // return $request->user();
});
