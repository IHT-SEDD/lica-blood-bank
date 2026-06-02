<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait InvalidateSelectCache
{
    public static function bootInvalidatesSelectCache(): void
    {
        $selectKey = static::$selectCacheKey ?? null;

        if (!$selectKey) return;

        // Otomatis flush cache saat create, update, delete
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function () use ($selectKey) {
                static::flushSelectCache($selectKey);
            });
        }
    }

    public static function flushSelectCache(string $key): void
    {
        // Hapus cache tanpa search (empty query)
        Cache::forget("select:{$key}:" . md5(''));
    }
}
