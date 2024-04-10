<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:19
 */
namespace Lany\MineAdmin\Listeners;
use JetBrains\PhpStorm\NoReturn;
use Lany\MineAdmin\Events\UserLoginBefore;

class UserLoginBeforeListener
{
    /**
     * 创建事件监听器
     */
    public function __construct()
    {
        // ...
    }

    /**
     * 处理事件
     */
    #[NoReturn] public function handle(UserLoginBefore $event): void
    {

    }
}