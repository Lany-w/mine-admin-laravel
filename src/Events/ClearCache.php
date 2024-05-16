<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/16 14:41
 */

namespace Lany\MineAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ClearCache
{
    use Dispatchable;

    public function __construct(public string $key)
    {

    }
}