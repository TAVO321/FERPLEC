<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tip;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TipController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('categoria')->trim()->toString();

        $query = Tip::published()->orderByDesc('published_at');
        $featured = null;

        if ($category !== '') {
            $query->where('category', $category);
        } else {
            $featured = Tip::published()
                ->where('is_featured', true)
                ->orderByDesc('published_at')
                ->first();

            if ($featured) {
                $query->whereKeyNot($featured->getKey());
            }
        }

        $tips = $query->paginate(12)->withQueryString();

        $categories = Tip::published()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('tips.index', [
            'tips' => $tips,
            'featured' => $featured,
            'categories' => $categories,
            'totalTips' => $tips->total(),
        ]);
    }

    public function show(Tip $tip): View
    {
        abort_unless($tip->is_published, 404);

        $relatedTips = Tip::published()
            ->whereKeyNot($tip->getKey())
            ->where('category', $tip->category)
            ->limit(4)
            ->get();

        $relatedProducts = $tip->related_products
            ? Product::active()->whereKey($tip->related_products)->get()
            : collect();

        $comments = $tip->approvedComments()->latest()->get();

        return view('tips.show', [
            'tip' => $tip,
            'relatedTips' => $relatedTips,
            'relatedProducts' => $relatedProducts,
            'comments' => $comments,
        ]);
    }
}
