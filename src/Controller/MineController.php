<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/3 09:57
 */
namespace Lany\MineAdmin\Controller;

use AllowDynamicProperties;
use Illuminate\Support\Str;
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

        $refClass = new \ReflectionClass($this);

        foreach($refClass->getProperties() as $property) {
            $serviceName = $property->getName();
            if (!Str::contains(strtolower($serviceName), 'service')) continue;
            $ref = new \ReflectionProperty($this, $serviceName);
            if (!$ref->getType()) continue;
            $serviceClass = new \ReflectionClass($ref->getType()->getName());
            $constructor = $serviceClass->getConstructor();
            $parameters = $constructor->getParameters();
            $params = [];
            foreach($parameters as $parameter) {
                if ($parameter->getClass()) {
                    $params[] = $parameter->getClass()->newInstance();
                }
                //$params[] = app($param->getType()->getName());
            }

            $this->$serviceName = $serviceClass->newInstanceArgs($params);
        }
    }

    protected function guard(): Guard|StatefulGuard
    {
        return Mine::guard();
    }

}