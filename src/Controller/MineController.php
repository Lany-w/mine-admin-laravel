<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 09:57
 */
namespace Lany\MineAdmin\Controller;


use Illuminate\Support\Facades\Auth;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Traits\ControllerTrait;

/**
 * 后台控制器基类
 * Class MineController.
 */
abstract class MineController
{
    use ControllerTrait;

    protected function guard(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
    {
        return Mine::guard();
    }

}