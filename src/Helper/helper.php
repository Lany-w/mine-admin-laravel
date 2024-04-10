<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 09:36
 */
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Foundation\Application;

if (!function_exists('t')) {
    function t($key = null,$replace = [],$locale = null): array|string|Translator|Application|null
    {
        return trans($key,$replace,$locale);
    }
}