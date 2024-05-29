<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/29 09:25
 */

namespace Lany\MineAdmin\Helper\Annotation\Handle;

use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Helper\Annotation\AbstractAnnotation;
use Lany\MineAdmin\Helper\Annotation\OperationLog;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Interfaces\MineAnnotation;

class OperationLogAnnotation extends  AbstractMineAnnotation implements MineAnnotation
{
    public static function getAnnotation($className = null): array|AbstractAnnotation
    {
        $route = request()->route();
        [$controller, $method] = explode('@', $route->getActionName());

        try {
            $controllerReflector = new \ReflectionClass($controller);
            $methodReflector = $controllerReflector->getMethod($method);
            // 获取方法的注解
            return self::getMethodAnnotations($methodReflector);

        } catch (MineException $e) {
            throw new MineException($e->getMessage());
        }
    }

    private static function getMethodAnnotations(\ReflectionMethod $method): object
    {
        if ($annotations = $method->getAttributes(OperationLog::class)) {
            return new OperationLog(...$annotations[0]->getArguments());
        }
        return new \stdClass();
    }
}