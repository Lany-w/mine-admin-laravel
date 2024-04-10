<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:11
 */
namespace Lany\MineAdmin\Events;
use Illuminate\Foundation\Events\Dispatchable;

class UserLoginBefore
{
    use Dispatchable;

    public function __construct(
        public array $data
    )
    {
    }
}