<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tip;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'products' => Product::count(),
            'activeProducts' => Product::where('is_active', true)->count(),
            'categories' => Category::count(),
            'guides' => Tip::count(),
            'lowStock' => Product::where('is_active', true)
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->take(10)
                ->get(),
            'recentProducts' => Product::latest()->take(5)->get(),
        ]);
    }
}
