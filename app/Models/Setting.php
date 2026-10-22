<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Clave de caché donde vive el volcado completo de la tabla.
     */
    public const CACHE_KEY = 'ferplec.settings.all';

    public const CACHE_TTL = 3600;

    /**
     * Devuelve un ajuste por clave, con cache del volcado completo.
     *
     * Cachear el array entero (y no una key por ajuste) evita una consulta
     * por cada ajuste solicitado y cubre el caso "la key no existe" sin
     * invalidar la cache en cada lectura.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return static::values()[$key] ?? $default;
    }

    /**
     * Volcado completo key => value, cacheado.
     *
     * @return array<string, mixed>
     */
    public static function values(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn (): array => static::query()->pluck('value', 'key')->all()
        );
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::flushCache();
    }

    /**
     * Guarda varios ajustes en una sola transacción e invalida la cache una vez.
     *
     * @param  array<string, mixed>  $values
     */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        static::flushCache();
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
