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
        $productStats = Product::query()
            ->selectRaw('COUNT(*) AS total, COALESCE(SUM(is_active), 0) AS active')
            ->first();

        return view('admin.dashboard', [
            'products' => (int) $productStats->total,
            'activeProducts' => (int) $productStats->active,
            'categories' => Category::count(),
            'guides' => Tip::count(),
            'lowStock' => Product::active()
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->take(10)
                ->get(),
            'recentProducts' => Product::with('category')->latest()->take(5)->get(),
        ]);
    }
}
