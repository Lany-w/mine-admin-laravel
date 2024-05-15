<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/7 13:42
 */

namespace Lany\MineAdmin\Interfaces;

interface MineAnnotation
{
    public static function getAnnotation($className = null): object|array;
}