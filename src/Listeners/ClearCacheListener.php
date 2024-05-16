<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/16 14:42
 */

namespace Lany\MineAdmin\Listeners;

use Lany\MineAdmin\Events\ClearCache;

class ClearCacheListener
{
    public function handle(ClearCache $event):void
    {
        cache()->store('redis')->forget($event->key);
    }
}