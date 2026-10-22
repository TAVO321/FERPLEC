<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tip;
use Illuminate\Http\Request;

class TipController extends Controller
{
    public function index(Request $request)
    {
        $categories = Tip::published()->orderBy('category')->distinct()->pluck('category');
        $totalTips = Tip::published()->count();

        $query = Tip::published();
        $featured = null;

        if ($request->filled('categoria')) {
            $query->where('category', $request->categoria);
        } else {
            $featured = Tip::published()->where('is_featured', true)->orderByDesc('published_at')->first();

            if ($featured) {
                $query->where('id', '!=', $featured->id);
            }
        }

        $tips = $query->orderByDesc('published_at')->paginate(12)->withQueryString();

        return view('guides.index', compact('tips', 'featured', 'categories', 'totalTips'));
    }

    public function show(Tip $tip)
    {
        if (! $tip->is_published) {
            abort(404);
        }
        $relatedTips = Tip::published()->where('id', '!=', $tip->id)->where('category', $tip->category)->limit(4)->get();
        $relatedProducts = collect();
        if ($tip->related_products) {
            $relatedProducts = Product::whereIn('id', $tip->related_products)->where('is_active', true)->get();
        }
        $comments = $tip->approvedComments()->latest()->get();

        return view('guides.show', compact('tip', 'relatedTips', 'relatedProducts', 'comments'));
    }
}
