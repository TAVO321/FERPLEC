<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Detecta N+1 en desarrollo: cualquier lazy load lanza excepción.
        Model::preventLazyLoading(! $this->app->isProduction());

        $this->loadBrandSettings();
    }

    /**
     * Los ajustes de marca viven en la base de datos (editables desde el
     * panel) con el .env como valor por defecto.
     */
    private function loadBrandSettings(): void
    {
        try {
            $settings = Setting::values();
        } catch (\Throwable) {
            return;
        }

        $map = [
            'ferplec.whatsapp' => $settings['whatsapp'] ?? null,
            'ferplec.brand' => $settings['site_name'] ?? null,
            'ferplec.tagline' => $settings['site_slogan'] ?? null,
        ];

        foreach ($map as $key => $value) {
            if (filled($value)) {
                Config::set($key, $value);
            }
        }

        View::share([
            'brandName' => Config::get('ferplec.brand'),
            'brandTagline' => Config::get('ferplec.tagline'),
        ]);
    }
}
