<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 09:17
 */

namespace Lany\MineAdmin\Traits;

use Illuminate\Support\Facades\Hash;

trait CreateBy
{
    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($elm) {
            $elm->created_by = user()->id;
            $elm->updated_by = user()->id;
        });

        self::updating(function($elm) {
            $elm->updated_by = user()->id;
        });
    }
}