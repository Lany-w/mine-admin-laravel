<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 09:57
 */
namespace Lany\MineAdmin\Controller;

use AllowDynamicProperties;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Traits\ControllerTrait;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use ReflectionException;

/**
 * 后台控制器基类
 * Class MineController.
 */
#[AllowDynamicProperties] abstract class MineController
{
    use ControllerTrait;
    protected mixed $request;

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->request = request();
        if (property_exists($this, 'service')) {
            $ref = new \ReflectionProperty($this, 'service');
            $serviceClass = new \ReflectionClass($ref->getType()->getName());
            $this->service = $serviceClass->newInstance();
        }
    }

    protected function guard(): Guard|StatefulGuard
    {
        return Mine::guard();
    }

}