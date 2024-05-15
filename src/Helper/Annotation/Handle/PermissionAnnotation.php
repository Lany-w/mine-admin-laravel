<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/7 13:40
 */

namespace Lany\MineAdmin\Helper\Annotation\Handle;

use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Helper\Annotation\AbstractAnnotation;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Interfaces\MineAnnotation;
use ReflectionClass;
use ReflectionMethod;
class PermissionAnnotation extends AbstractMineAnnotation implements MineAnnotation
{
    /**
     * @param null $className
     * @throws \ReflectionException
     */
    public static function getAnnotation($className = null): array|AbstractAnnotation
    {
        $route = request()->route();
        [$controller, $method] = explode('@', $route->getActionName());

        try {
            $controllerReflector = new ReflectionClass($controller);
            $methodReflector = $controllerReflector->getMethod($method);
            // 获取方法的注解
            return self::getMethodAnnotations($methodReflector);

        } catch (MineException $e) {
            throw new MineException($e->getMessage());
        }
    }

    // 获取方法的注解
    private static function getMethodAnnotations(ReflectionMethod $method): object
    {
        if ($annotations = $method->getAttributes(Permission::class)) {
            return new Permission(...$annotations[0]->getArguments());
        }
        return new Permission();
    }

}