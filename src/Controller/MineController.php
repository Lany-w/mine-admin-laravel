<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 09:57
 */
namespace Lany\MineAdmin\Controller;

use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Traits\ControllerTrait;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

/**
 * 后台控制器基类
 * Class MineController.
 */
abstract class MineController
{
    use ControllerTrait;
    protected mixed $request;

    public function __construct()
    {
        $this->request = request();
    }

    protected function guard(): Guard|StatefulGuard
    {
        return Mine::guard();
    }

}