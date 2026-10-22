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
        return view('admin.settings.index', [
            'whatsapp' => Setting::get('whatsapp_number', Setting::get('whatsapp', config('ferplec.whatsapp'))),
            'site_name' => Setting::get('site_name', config('ferplec.brand')),
            'site_slogan' => Setting::get('site_slogan', config('ferplec.tagline')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'whatsapp' => ['required', 'string', 'max:30'],
            'site_name' => ['nullable', 'string', 'max:100'],
            'site_slogan' => ['nullable', 'string', 'max:150'],
        ]);

        Setting::set('whatsapp_number', $data['whatsapp']);
        Setting::set('whatsapp', $data['whatsapp']);
        if (isset($data['site_name'])) {
            Setting::set('site_name', $data['site_name']);
        }
        if (isset($data['site_slogan'])) {
            Setting::set('site_slogan', $data['site_slogan']);
        }

        return back()->with('status', 'Configuración guardada correctamente.');
    }
}
