<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    /**
     * Build a public URL for a stored media path, using the configured disk.
     * Falls back to the local /storage asset for paths that don't exist on the disk.
     */
    function media_url(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//')) {
            return $path;
        }

        $path = ltrim($path, '/');

        $disk = config('filesystems.disks.'.config('filesystems.default'));

        if (($disk['driver'] ?? 'local') === 'local') {
            return asset('storage/'.$path);
        }

        return Storage::disk(config('filesystems.default'))->url($path);
    }
}
