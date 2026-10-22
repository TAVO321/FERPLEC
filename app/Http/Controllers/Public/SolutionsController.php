<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class SolutionsController extends Controller
{
    public function index()
    {
        return view('soluciones.index');
    }
}
