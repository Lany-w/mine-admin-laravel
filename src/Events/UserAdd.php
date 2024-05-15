<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 10:50
 */

namespace Lany\MineAdmin\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserAdd
{
    use Dispatchable;
    public function __construct(
        public array $userInfo
    )
    {
    }
}