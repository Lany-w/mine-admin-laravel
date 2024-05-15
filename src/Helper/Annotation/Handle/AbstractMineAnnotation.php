<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/7 13:43
 */

namespace Lany\MineAdmin\Helper\Annotation\Handle;

use Lany\MineAdmin\Helper\Annotation\AbstractAnnotation;
use Lany\MineAdmin\Helper\Annotation\ExcelProperty;

abstract class AbstractMineAnnotation
{
    /**
     * @throws \ReflectionException
     */
    public static function getAnnotation($className = null): array|AbstractAnnotation
    {
        $propertyArr = [];
        $properties = (new \ReflectionClass($className))->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach($properties as $property) {
            $refProperty = (new \ReflectionProperty($property->class, $property->name))->getAttributes(ExcelProperty::class)[0];
            $array = (new \ReflectionClass($refProperty->getName()))->newInstance(...$refProperty->getArguments())->toArray();
            $array['name'] = $property->getName();
            $propertyArr[] = $array;
        }

        return $propertyArr;
    }
}