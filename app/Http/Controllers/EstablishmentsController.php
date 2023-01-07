<?php

namespace App\Http\Controllers;

use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentsController extends Controller
{
    public function index()
    {
        return Establishment::paginate(10);
    }
}
