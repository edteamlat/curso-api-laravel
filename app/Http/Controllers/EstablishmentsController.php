<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstablishmentResource;
use Illuminate\Http\Request;
use App\Models\Establishment;
use Illuminate\Support\Facades\Auth;

class EstablishmentsController extends Controller
{
    public function index()
    {
        abort_unless(Auth::user()->tokenCan('establishment:show'), 403, "You don't have permissions to perform this action.");

        $establishments = Establishment::when(request()->filled('category'), function ($query) {
                $query->where('category', request('category'));
            })
            ->when(request()->exists('popular'), function ($query) {
                $query->orderBy('stars', 'DESC');
            })
            ->paginate(10);

        return EstablishmentResource::collection($establishments);
    }

    public function show(Establishment $establishment)
    {
        abort_unless(Auth::user()->tokenCan('establishment:show'), 403, "You don't have permissions to perform this action.");

        $establishment->load('products');

        return new EstablishmentResource($establishment);
    }
}
