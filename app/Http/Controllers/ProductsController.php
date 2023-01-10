<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;

class ProductsController extends Controller
{
    public function show(Product $product)
    {
        abort_unless(Auth::user()->tokenCan('product:show'), 403, "You don't have permissions to perform this action.");

        return new ProductResource($product);
    }
}
