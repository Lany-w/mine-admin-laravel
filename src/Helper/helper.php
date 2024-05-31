<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 09:36
 */
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Redis;
use Lany\MineAdmin\Mine;
use Illuminate\Support\Str;

if (!function_exists('t')) {
    function t($key = null,$replace = [],$locale = null): array|string|Translator|Application|null
    {
        return trans($key,$replace,$locale);
    }
}

if (!function_exists('user')) {
    function user(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Mine::guard()->user();
    }
}


if (! function_exists('is_in_container')) {
    function is_in_container(): bool
    {
        if (! file_exists('/proc/self/mountinfo')) {
            return false;
        }
        $mountinfo = file_get_contents('/proc/self/mountinfo');
        return strpos($mountinfo, 'kubepods') > 0 || strpos($mountinfo, 'docker') > 0;
    }
}

if (!function_exists('redis')) {
    function redis(): \Illuminate\Redis\Connections\Connection
    {
        return Redis::connection('cache');
    }
}

if (!function_exists('fullCacheKey')) {
    function fullCacheKey($key): string
    {
        return config('database.redis.options.prefix').config('cache.prefix').':'.$key;
    }
}

if (!function_exists('cacheKey')) {
    function cacheKey($key): string
    {
        if (Str::startsWith($key, config('database.redis.options.prefix'))) {
            return Str::remove(config('database.redis.options.prefix'), $key);
        }
        return config('cache.prefix').':'.$key;
    }
}

if (! function_exists('format_size')) {
    /**
     * 格式化大小.
     */
    function format_size(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $index = 0;
        for ($i = 0; $size >= 1024 && $i < 5; ++$i) {
            $size /= 1024;
            $index = $i;
        }
        return round($size, 2) . $units[$index];
    }
}

if (!function_exists('deleteCache')) {
    function deleteCache(?string $keys = null): void
    {
        $redis = redis();

        if ($keys) {
            $keys = explode(',', $keys);
            $iterator = null;
            $n = [];
            foreach ($keys as $key) {
                if (! Str::contains($key, '*')) {
                    $n[] = cacheKey($key);
                } else {
                    do {
                        $k = $redis->command('SCAN', [$iterator, fullCacheKey($key), 100]);
                        if (!empty($k)) $redis->command('del', array_map('cacheKey', $k));

                    } while ($iterator != 0);
                }
            }
            if (!empty($n)) $redis->command('del', $n);
        }
    }
}

