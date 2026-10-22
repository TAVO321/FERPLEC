<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TipRequest;
use App\Models\Tip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TipAdminController extends Controller
{
    /**
     * Áreas disponibles para clasificar un tip.
     *
     * @return array<string, string>
     */
    private const AREAS = [
        'Electricidad' => 'Electricidad',
        'Plomería' => 'Plomería',
        'Ferretería' => 'Ferretería',
        'Construcción' => 'Construcción',
    ];

    public function index(): View
    {
        return view('admin.tips.index', [
            'tips' => Tip::orderByDesc('updated_at')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.tips.create', ['areas' => self::AREAS]);
    }

    public function store(TipRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['title'], $data['slug'] ?? null);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['read_time'] = (int) ($data['read_time'] ?? 3);

        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('tips', 'public');
        } elseif (($data['image'] ?? null) === '') {
            $data['image'] = null;
        }

        unset($data['image_file'], $data['remove_image']);

        Tip::create($data);

        return redirect()->route('admin.tips.index')->with('status', 'Tip creado correctamente.');
    }

    public function edit(Tip $tip): View
    {
        return view('admin.tips.edit', ['tip' => $tip, 'areas' => self::AREAS]);
    }

    public function update(TipRequest $request, Tip $tip): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['title'], $data['slug'] ?? null, $tip->id);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['read_time'] = (int) ($data['read_time'] ?? $tip->read_time ?? 3);

        if ($data['is_published'] && ! $tip->published_at) {
            $data['published_at'] = now();
        }

        if ($request->boolean('remove_image')) {
            $this->deleteImage($tip->image);
            $data['image'] = null;
        }

        if ($request->hasFile('image_file')) {
            $this->deleteImage($tip->image);
            $data['image'] = $request->file('image_file')->store('tips', 'public');
        } elseif (($data['image'] ?? null) === '') {
            $data['image'] = null;
        }

        unset($data['image_file'], $data['remove_image']);

        $tip->update($data);

        return redirect()->route('admin.tips.index')->with('status', 'Tip actualizado correctamente.');
    }

    public function destroy(Tip $tip): RedirectResponse
    {
        $this->deleteImage($tip->image);
        $tip->delete();

        return redirect()->route('admin.tips.index')->with('status', 'Tip eliminado correctamente.');
    }

    /**
     * Genera un slug único a partir del título, ignorando el tip actual.
     */
    private function uniqueSlug(string $title, ?string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug ?: $title);
        $candidate = $base;
        $suffix = 2;

        while (Tip::where('slug', $candidate)->when($ignoreId, fn ($q) => $q->where('id', '<>', $ignoreId))->exists()) {
            $candidate = $base.'-'.$suffix++;
        }

        return $candidate;
    }

    private function deleteImage(?string $path): void
    {
        if ($path && ! Str::startsWith($path, ['http://', 'https://'])) {
            Storage::disk('public')->delete($path);
        }
    }
}
