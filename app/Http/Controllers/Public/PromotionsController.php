<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;

class PromotionsController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->whereIn('position', ['promotion', 'home_promo', 'secondary'])->orderBy('sort_order')->get();

        return view('promociones.index', compact('banners'));
    }
}
