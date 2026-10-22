<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductController extends Controller
{
    /**
     * Columnas del CSV de exportación/importación.
     *
     * @var list<string>
     */
    private const CSV_COLUMNS = [
        'code', 'name', 'description', 'brand', 'categoria',
        'price', 'stock_total', 'stock_apartado', 'stock_vendido',
        'is_active', 'is_featured',
    ];

    /**
     * Campos de stock/Precio que se pueden ajustar en línea.
     *
     * @var list<string>
     */
    private const ADJUSTABLE_FIELDS = ['price', 'stock', 'stock_apartado', 'stock_vendido'];

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
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('categoria')));
        }

        return view('admin.products.index', [
            'products' => $query->paginate(20)->withQueryString(),
            'categories' => Category::listCached(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::listCached(),
        ]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $this->payload($request);

        if ($image = $this->handleImage($request)) {
            $data['images'] = [$image];
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::listCached(),
        ]);
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $this->payload($request);

        if ($request->boolean('remove_image')) {
            $this->deleteImage($product->main_image);
            $data['images'] = null;
        } elseif ($image = $this->handleImage($request, $product)) {
            $this->deleteImage($product->main_image);
            $data['images'] = [$image];
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteImage($product->main_image);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado.');
    }

    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'field' => ['required', 'in:'.implode(',', self::ADJUSTABLE_FIELDS)],
            'value' => ['required', 'numeric', 'min:0'],
        ]);

        $field = $data['field'];
        $previous = (float) $product->{$field};
        $new = (float) $data['value'];

        if ($previous === $new) {
            return back()->with('success', 'Sin cambios.');
        }

        $product->update([$field => $field === 'price' ? $new : (int) $new]);

        StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'type' => 'ajuste',
            'field' => $field,
            'previous_value' => (string) $previous,
            'new_value' => (string) $new,
            'quantity' => (int) abs($new - $previous),
            'stock_before' => (int) $product->stock,
            'stock_after' => (int) $product->fresh()->stock,
        ]);

        return back()->with('success', 'Actualizado.');
    }

    public function export(): StreamedResponse
    {
        $products = Product::with('category')->orderBy('code')->get();

        return response()->streamDownload(function () use ($products): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, self::CSV_COLUMNS);

            foreach ($products as $p) {
                fputcsv($out, [
                    $p->code,
                    $p->name,
                    $p->description,
                    $p->brand,
                    $p->category?->slug,
                    $p->price,
                    $p->stock,
                    $p->stock_apartado,
                    $p->stock_vendido,
                    $p->is_active ? 1 : 0,
                    $p->is_featured ? 1 : 0,
                ]);
            }

            fclose($out);
        }, 'productos-ferplec-'.date('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=utf-8']);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:8192'],
        ]);

        $rows = $this->readCsv($request->file('file')->getRealPath());

        if ($rows === null) {
            return back()->with('error', 'El CSV no tiene la columna "code".');
        }

        if ($rows === []) {
            return back()->with('error', 'El CSV no contiene filas de datos.');
        }

        $categories = Category::pluck('id', 'slug');
        $defaultCategoryId = $categories->first();
        $now = now();

        // Una sola consulta para saber qué códigos ya existen.
        $codes = array_column($rows, 'code');
        $existing = Product::whereIn('code', $codes)
            ->pluck('slug', 'code');

        $payload = [];
        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {
            $code = $row['code'];
            $isNew = ! isset($existing[$code]);

            $payload[] = [
                'code' => $code,
                'slug' => $existing[$code] ?? Str::slug($row['name'] ?: $code).'-'.Str::lower($code),
                'name' => $row['name'] ?: $code,
                'description' => $row['description'] !== '' ? $row['description'] : null,
                'brand' => $row['brand'] !== '' ? $row['brand'] : null,
                'category_id' => $categories[$row['categoria']] ?? $defaultCategoryId,
                'price' => $row['price'] !== '' ? $row['price'] : null,
                'unit' => 'unidad',
                'stock' => $row['stock_total'],
                'stock_apartado' => $row['stock_apartado'],
                'stock_vendido' => $row['stock_vendido'],
                'is_active' => $row['is_active'],
                'is_featured' => $row['is_featured'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $isNew ? $created++ : $updated++;
        }

        DB::transaction(function () use ($payload): void {
            foreach (array_chunk($payload, 500) as $chunk) {
                Product::upsert(
                    $chunk,
                    ['code'],
                    ['slug', 'name', 'description', 'brand', 'category_id', 'price', 'unit',
                        'stock', 'stock_apartado', 'stock_vendido', 'is_active', 'is_featured', 'updated_at']
                );
            }
        });

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

    /**
     * Normaliza el CSV a un array con las columnas esperadas.
     *
     * @return list<array<string, mixed>>|null null si el CSV no es válido
     */
    private function readCsv(string $path): ?array
    {
        $handle = fopen($path, 'r');

        if (! $handle) {
            return null;
        }

        $header = fgetcsv($handle);

        if (! $header) {
            fclose($handle);

            return null;
        }

        $header = array_map(fn ($h) => strtolower(trim((string) $h)), $header);

        if (! in_array('code', $header, true)) {
            fclose($handle);

            return null;
        }

        $rows = [];

        while (($raw = fgetcsv($handle)) !== false) {
            if ($raw === [null] || $raw === []) {
                continue;
            }

            // array_combine falla si la fila tiene más columnas que la cabecera.
            $raw = array_slice($raw, 0, count($header));
            $row = array_combine($header, array_pad($raw, count($header), ''));

            $code = trim((string) ($row['code'] ?? ''));

            if ($code === '') {
                continue;
            }

            $rows[] = [
                'code' => $code,
                'name' => trim((string) ($row['name'] ?? '')),
                'description' => trim((string) ($row['description'] ?? '')),
                'brand' => trim((string) ($row['brand'] ?? '')),
                'categoria' => trim((string) ($row['categoria'] ?? '')),
                'price' => trim((string) ($row['price'] ?? '')),
                'stock_total' => (int) ($row['stock_total'] ?? $row['stock'] ?? 0),
                'stock_apartado' => (int) ($row['stock_apartado'] ?? 0),
                'stock_vendido' => (int) ($row['stock_vendido'] ?? 0),
                'is_active' => $this->toBool($row['is_active'] ?? null),
                'is_featured' => $this->toBool($row['is_featured'] ?? null),
            ];
        }

        fclose($handle);

        return $rows;
    }

    private function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'true', 'si', 'sí', 'yes'], true);
    }

    /**
     * Normaliza los datos validados a las columnas reales de la tabla.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function payload(ProductRequest $request): array
    {
        $data = $request->validated();

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['unit'] = ($data['unit'] ?? null) ?: 'unidad';
        $data['min_stock'] = (int) ($data['min_stock'] ?? 0);
        $data['stock'] = (int) ($data['stock'] ?? 0);
        $data['stock_apartado'] = (int) ($data['stock_apartado'] ?? 0);
        $data['stock_vendido'] = (int) ($data['stock_vendido'] ?? 0);

        unset($data['image'], $data['remove_image']);

        return $data;
    }

    private function handleImage(Request $request, ?Product $product = null): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('products', 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
