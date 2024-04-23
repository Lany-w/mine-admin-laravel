<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 09:36
 */
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Foundation\Application;
use Lany\MineAdmin\Mine;

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
    function redis()
    {
        return app('redis');
    }
}

