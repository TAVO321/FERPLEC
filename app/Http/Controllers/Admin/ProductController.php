<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category')->latest();

        if ($request->filled('q')) {
            $q = $request->string('q');
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%");
            });
        }

        if ($request->filled('categoria')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->categoria));
        }

        return view('admin.products.index', [
            'products' => $query->paginate(20)->withQueryString(),
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $image = $this->handleImage($request);
        if ($image) {
            $data['images'] = [$image];
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);

        if ($image = $this->handleImage($request, $product)) {
            $images = $product->images ?? [];
            $images = [$image];
            $data['images'] = $images;
        }

        if ($request->boolean('remove_image')) {
            if ($product->main_image && ! Str::startsWith($product->main_image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['images'] = null;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->main_image && ! Str::startsWith($product->main_image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->main_image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'field' => ['required', 'in:price,stock_total,stock_apartado,stock_vendido'],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        $field = $data['field'];
        $previous = $product->{$field};
        $new = $data['value'];

        if ((string) $previous === (string) $new) {
            return back()->with('success', 'Sin cambios.');
        }

        $product->update([$field => $new]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'type' => 'ajuste',
            'field' => $field,
            'previous_value' => (string) $previous,
            'new_value' => (string) $new,
            'quantity' => (int) abs((float) $new - (float) $previous),
            'stock_before' => (int) $previous,
            'stock_after' => (int) $new,
        ]);

        return back()->with('success', 'Actualizado.');
    }

    public function export(): StreamedResponse
    {
        $products = Product::with('category')->orderBy('code')->get();

        return response()->streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['code', 'name', 'description', 'brand', 'categoria', 'price', 'stock_total', 'stock_apartado', 'stock_vendido', 'is_active', 'is_featured']);

            foreach ($products as $p) {
                fputcsv($out, [
                    $p->code,
                    $p->name,
                    $p->description,
                    $p->brand,
                    $p->category->slug,
                    $p->price,
                    $p->stock,
                    $p->stock_apartado,
                    $p->stock_vendido,
                    $p->is_active ? 1 : 0,
                    $p->is_featured ? 1 : 0,
                ]);
            }

            fclose($out);
        }, 'productos-ferplec-'.date('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $handle = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($handle);

        if (! $header || ! in_array('code', array_map('strtolower', $header), true)) {
            fclose($handle);

            return back()->with('error', 'El CSV no tiene la columna "code".');
        }

        $header = array_map(fn ($h) => strtolower(trim($h)), $header);
        $categories = Category::pluck('id', 'slug');
        $created = 0;
        $updated = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, array_pad($row, count($header), null));
            $code = trim((string) ($data['code'] ?? ''));

            if ($code === '') {
                continue;
            }

            $categoryId = $categories[$data['categoria'] ?? ''] ?? $categories->first();

            $attributes = [
                'name' => $data['name'] ?? $code,
                'description' => $data['description'] ?? null,
                'brand' => $data['brand'] ?? null,
                'category_id' => $categoryId,
                'price' => ($data['price'] ?? '') !== '' ? $data['price'] : null,
                'stock' => (int) ($data['stock_total'] ?? $data['stock'] ?? 0),
                'stock_apartado' => (int) ($data['stock_apartado'] ?? 0),
                'stock_vendido' => (int) ($data['stock_vendido'] ?? 0),
                'is_active' => ! empty($data['is_active']),
                'is_featured' => ! empty($data['is_featured']),
            ];

            $product = Product::where('code', $code)->first();

            if ($product) {
                $product->update($attributes);
                $updated++;
            } else {
                Product::create(['code' => $code, 'slug' => Str::slug($attributes['name'] ?? $code).'-'.strtolower($code)] + $attributes);
                $created++;
            }
        }

        fclose($handle);

        return back()->with('success', "Importación lista: {$created} creados, {$updated} actualizados.");
    }

    public function movements(Request $request): View
    {
        return view('admin.products.movements', [
            'movements' => StockMovement::with(['product', 'user'])
                ->latest()
                ->paginate(30),
        ]);
    }

    private function handleImage(Request $request, ?Product $product = null): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $request->validate([
            'image' => ['image', 'max:2048'],
        ]);

        if ($product?->main_image && ! Str::startsWith($product->main_image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($product->main_image);
        }

        return $request->file('image')->store('products', 'public');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:products,code'.($product ? ','.$product->id : '')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:30'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'stock_total' => ['nullable', 'integer', 'min:0'],
            'stock_apartado' => ['nullable', 'integer', 'min:0'],
            'stock_vendido' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        if (empty($data['unit'])) {
            $data['unit'] = 'unidad';
        }
        $data['min_stock'] = isset($data['min_stock']) ? (int) $data['min_stock'] : 0;
        $stock = $data['stock'] ?? $data['stock_total'] ?? ($product->stock ?? 0);
        $data['stock'] = (int) $stock;
        $data['stock_apartado'] = (int) ($data['stock_apartado'] ?? ($product->stock_apartado ?? 0));
        $data['stock_vendido'] = (int) ($data['stock_vendido'] ?? ($product->stock_vendido ?? 0));
        unset($data['stock_total']);

        return $data;
    }
}
