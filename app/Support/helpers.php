<?php

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
