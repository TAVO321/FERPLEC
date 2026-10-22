<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::values();

        return view('admin.settings.index', [
            'whatsapp' => $settings['whatsapp'] ?? config('ferplec.whatsapp'),
            'site_name' => $settings['site_name'] ?? config('ferplec.brand'),
            'site_slogan' => $settings['site_slogan'] ?? config('ferplec.tagline'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'whatsapp' => ['required', 'string', 'max:30'],
            'site_name' => ['nullable', 'string', 'max:100'],
            'site_slogan' => ['nullable', 'string', 'max:150'],
        ]);

        $values = [
            'whatsapp' => $data['whatsapp'],
        ];

        if (filled($data['site_name'] ?? null)) {
            $values['site_name'] = $data['site_name'];
        }

        if (filled($data['site_slogan'] ?? null)) {
            $values['site_slogan'] = $data['site_slogan'];
        }

        Setting::setMany($values);

        return back()->with('status', 'Configuración guardada correctamente.');
    }
}
