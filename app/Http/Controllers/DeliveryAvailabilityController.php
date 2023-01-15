<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryAvailabilityController extends Controller
{
    public function update()
    {
        abort_unless(Auth::user()->tokenCan('availability:update'), 403, "You don't have permissions to perform this action.");
        request()->validate([
            'status' => 'required|boolean',
        ]);

        $user = Auth::user();
        $user->config = array_merge($user->config, [
            'availability' => (bool) request('status')
        ]);
        $user->save();

        return new UserResource($user);
    }
}
