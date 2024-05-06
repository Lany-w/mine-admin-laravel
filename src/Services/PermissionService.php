<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/30 16:13
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Exceptions\NoPermissionException;
use Lany\MineAdmin\Helper\Permission;
use ReflectionClass;
use ReflectionMethod;
class PermissionService
{
    /**
     * @throws \ReflectionException
     */
    public function getPermissionAnnotation(): object
    {
        $route = request()->route();
        [$controller, $method] = explode('@', $route->getActionName());

        try {
            $controllerReflector = new ReflectionClass($controller);
            $methodReflector = $controllerReflector->getMethod($method);
            // 获取方法的注解
            return $this->getMethodAnnotations($methodReflector);

        } catch (MineException $e) {

        }
        return new \stdClass();
    }

    // 获取方法的注解
    private function getMethodAnnotations(ReflectionMethod $method): object
    {
        if ($annotations = $method->getAttributes(Permission::class)) {
            return new Permission(...$annotations[0]->getArguments());
        }
        return new Permission();
    }

}