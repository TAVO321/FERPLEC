<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tip;

class HomeController extends Controller
{
    public function index()
    {
        $heroBanner = Banner::active()->where('position', 'home_hero')->orderBy('sort_order')->first();
        $mainCategories = Category::where('is_active', true)->whereNull('parent_id')->where('is_featured', true)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();
        if ($mainCategories->isEmpty()) {
            $mainCategories = Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->limit(3)->get();
        }
        $subcategories = Category::where('is_active', true)->whereNotNull('parent_id')
            ->withCount('subcategoryProducts')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->orderBy('sort_order')->limit(4)->get();
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::where('is_active', true)->orderBy('views', 'desc')->limit(4)->get();
        }
        $tips = Tip::published()->orderByDesc('is_featured')->orderByDesc('published_at')->limit(8)->get();
        $promos = Banner::active()->whereIn('position', ['home_promo', 'promotion'])->orderBy('sort_order')->get();

        return view('home.index', compact('heroBanner', 'mainCategories', 'subcategories', 'featuredProducts', 'tips', 'promos'));
    }
}
