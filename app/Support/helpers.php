<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    /**
     * Build a public URL for a stored media path.
     *
     * Uses the `public` disk, which transparently switches between the local
     * filesystem and an S3/R2 bucket depending on whether AWS_BUCKET is set.
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        return Storage::disk('public')->url(ltrim($path, '/'));
    }
}

if (! function_exists('wa_link')) {
    /**
     * Build a wa.me link using the WhatsApp number stored in Settings,
     * falling back to the configuration/env value.
     */
    function wa_link(?string $message = null): string
    {
        $number = null;

        try {
            $number = Setting::get('whatsapp');
        } catch (Throwable) {
            $number = null;
        }

        $number ??= config('ferplec.whatsapp');
        $number = preg_replace('/\D+/', '', (string) $number);

        $url = 'https://wa.me/'.$number;

        if (filled($message)) {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }
}
