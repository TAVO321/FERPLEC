<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\Tip;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $whatsapp = Setting::get('whatsapp_number') ?? Setting::get('whatsapp');
            if ($whatsapp) {
                config(['ferplec.whatsapp' => $whatsapp]);
            }
        } catch (\Throwable $e) {
            //
        }

        Route::bind('tip', function ($value) {
            if (is_numeric($value)) {
                $tip = Tip::find($value);
                if ($tip) {
                    return $tip;
                }
            }
            $tip = Tip::where('slug', $value)->first();
            if ($tip) {
                return $tip;
            }

            return Tip::findOrFail($value);
        });
    }
}
