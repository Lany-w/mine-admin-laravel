<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 11:18
 */

namespace Lany\MineAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserDelete
{
    use Dispatchable;


    public function __construct(
        public array $ids
    )
    {
    }
}