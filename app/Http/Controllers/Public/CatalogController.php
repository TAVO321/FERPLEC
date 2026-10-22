<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::active();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('manufacturer', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $selected = Category::find($request->integer('category'));

            if ($selected?->parent_id) {
                $query->where('subcategory_id', $selected->id);
            } else {
                $query->where('category_id', $request->integer('category'));
            }
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->integer('subcategory'));
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->string('brand'));
        }

        if ($request->filled('disponible') && $request->input('disponible') === '1') {
            $query->where('stock', '>', 0);
        }

        $sort = $request->input('sort', 'relevancia');

        if ($sort === 'precio_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'precio_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'nombre') {
            $query->orderBy('name');
        } else {
            $query->orderByDesc('is_featured')->orderBy('sort_order')->orderBy('name');
        }
        $products = $query->paginate(24)->withQueryString();

        $categories = Category::active()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return view('catalog.index', compact('products', 'categories'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->increment('views');

        $related = Product::active()
            ->whereKeyNot($product->getKey())
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                    ->orWhere('subcategory_id', $product->subcategory_id);
            })
            ->limit(8)
            ->get();

        return view('product.show', compact('product', 'related'));
    }
}
