<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseService
{
    protected int $cacheTTL = 3600; // 1 hour default cache

    protected function remember(string $key, callable $callback, ?int $ttl = null): mixed
    {
        return Cache::remember($key, $ttl ?? $this->cacheTTL, $callback);
    }

    protected function forget(string $pattern): void
    {
        // Clear cache by pattern if needed
        Cache::flush();
    }

    protected function getCacheKey(string $prefix, array $params = []): string
    {
        return $prefix . ':' . md5(serialize($params));
    }
}
