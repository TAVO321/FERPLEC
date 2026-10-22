<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuideAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.guides.index', [
            'guides' => Tip::orderByDesc('updated_at')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.guides.create', ['areas' => $this->categories()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['read_time'] = (int) ($data['read_time'] ?? 3);
        if ($data['is_published'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($request->hasFile('image_file')) {
            $data['image'] = $request->file('image_file')->store('tips', 'public');
        } elseif (array_key_exists('image', $data) && $data['image'] === '') {
            $data['image'] = null;
        }

        unset($data['image_file'], $data['remove_image']);

        Tip::create($data);

        return redirect()->route('admin.tips.index')->with('status', 'Tip creado correctamente.');
    }

    public function edit($tip): View
    {
        $tip = $tip instanceof Tip ? $tip : Tip::findOrFail($tip);

        return view('admin.guides.edit', ['guide' => $tip, 'areas' => $this->categories()]);
    }

    public function update(Request $request, $tip): RedirectResponse
    {
        $tip = $tip instanceof Tip ? $tip : Tip::findOrFail($tip);
        $data = $this->validated($request, $tip);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_published'] = $request->boolean('is_published');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['read_time'] = (int) ($data['read_time'] ?? $tip->read_time ?? 3);
        if ($data['is_published'] && ! $tip->published_at) {
            $data['published_at'] = now();
        }

        if ($request->boolean('remove_image')) {
            if ($tip->image && ! Str::startsWith($tip->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($tip->image);
            }
            $data['image'] = null;
        }

        if ($request->hasFile('image_file')) {
            if ($tip->image && ! Str::startsWith($tip->image, ['http://', 'https://'])) {
                Storage::disk('public')->delete($tip->image);
            }
            $data['image'] = $request->file('image_file')->store('tips', 'public');
        } elseif (array_key_exists('image', $data) && $data['image'] === '') {
            $data['image'] = null;
        }

        unset($data['image_file'], $data['remove_image']);

        $tip->update($data);

        return redirect()->route('admin.tips.index')->with('status', 'Tip actualizado correctamente.');
    }

    public function destroy($tip): RedirectResponse
    {
        $tip = $tip instanceof Tip ? $tip : Tip::findOrFail($tip);
        if ($tip->image && ! Str::startsWith($tip->image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($tip->image);
        }
        $tip->delete();

        return redirect()->route('admin.tips.index')->with('status', 'Tip eliminado correctamente.');
    }

    private function validated(Request $request, ?Tip $guide = null): array
    {
        $slugRule = $guide ? 'nullable|string|max:255|unique:tips,slug,'.$guide->id : 'nullable|string|max:255|unique:tips,slug';

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => [$slugRule],
            'category' => ['required', 'string', 'max:100'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
            'read_time' => ['nullable', 'integer', 'min:1', 'max:60'],
            'published_at' => ['nullable', 'date'],
        ]);

        return $data;
    }

    private function categories(): array
    {
        return [
            'Electricidad' => 'Electricidad',
            'Plomería' => 'Plomería',
            'Ferretería' => 'Ferretería',
            'Construcción' => 'Construcción',
        ];
    }
}
