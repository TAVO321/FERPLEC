<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory'])->where('is_active', true);

        if ($request->filled('q')) {
            $q = $request->q;
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
            $selected = Category::find($request->category);

            if ($selected?->parent_id) {
                $query->where('subcategory_id', $selected->id);
            } else {
                $query->where('category_id', $request->category);
            }
        }

        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('disponible') && $request->disponible === '1') {
            $query->where('stock', '>', 0);
        }

        $sort = $request->get('sort', 'relevancia');
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
        $categories = Category::where('is_active', true)->whereNull('parent_id')->with('children')->orderBy('sort_order')->get();
        $brands = Product::where('is_active', true)->whereNotNull('brand')->distinct()->pluck('brand');

        return view('catalog.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }
        $product->increment('views');
        $related = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)->orWhere('subcategory_id', $product->subcategory_id);
            })
            ->limit(8)
            ->get();

        return view('product.show', compact('product', 'related'));
    }
}
