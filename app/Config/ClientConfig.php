<?php

namespace App\Config;

use Illuminate\Support\Str;

class ClientConfig
{
    protected static ?string $cachedSlug = null;

    /**
     * Ambil slug client aktif dari config('app.client_data.name')
     */
    public static function slug(): string
    {
        if (static::$cachedSlug !== null) {
            return static::$cachedSlug;
        }

        $clientName = config('app.client_data.name');

        return static::$cachedSlug = Str::of($clientName)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();
    }

    /**
     * Ambil nilai config untuk module tertentu, dengan fallback ke default.
     *
     * Contoh: ClientConfig::get('blood_transfusion', 'recommendation_blood_bag')
     */
    public static function get(string $module, string $key, mixed $fallback = null): mixed
    {
        $slug = static::slug();

        $clientValue = config("client_configuration.clients.{$slug}.{$module}.{$key}");
        if ($clientValue !== null) {
            return $clientValue;
        }

        $defaultValue = config("client_configuration.default.{$module}.{$key}");
        if ($defaultValue !== null) {
            return $defaultValue;
        }

        return $fallback;
    }

    /**
     * Ambil seluruh config satu module (merge default + override client),
     * berguna untuk di-passing ke JS sekaligus.
     */
    public static function module(string $module): array
    {
        $slug = static::slug();

        $default = config("client_configuration.default.{$module}", []);
        $override = config("client_configuration.clients.{$slug}.{$module}", []);

        return array_merge($default, $override);
    }
}
