<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }
}
