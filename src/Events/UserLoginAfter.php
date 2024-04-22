<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:11
 */
namespace Lany\MineAdmin\Events;
use Illuminate\Foundation\Events\Dispatchable;
use Lany\MineAdmin\Model\SystemUser;

class UserLoginAfter
{
    use Dispatchable;

    public function __construct(
        public SystemUser $systemUser,
        public bool|int $loginStatus,
        public string $token
    )
    {
    }
}