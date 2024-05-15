<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 16:33
 */

namespace Lany\MineAdmin\Helper\Annotation;

use Illuminate\Contracts\Support\Arrayable;
use Lany\MineAdmin\Services\SystemDictDataService;
use ReflectionProperty;
abstract class AbstractAnnotation implements Arrayable
{
    public function toArray(): array
    {
        $properties = (new \ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC);
        $result = [];
        foreach ($properties as $property) {
            if ($property->getName() == 'dictName') {
                $data = '';
                if ($property->getValue($this)) {
                    $data = collect(app(SystemDictDataService::class)->getList(['code' => $property->getValue($this)]))->pluck('title', 'key')->toArray();
                }
                $result[$property->getName()] = $data;
            } else {
                $result[$property->getName()] = $property->getValue($this);
            }
        }
        if (isset($result[0]['index'])) {
            $collection = collect($result)->sortBy('index');
            $result = $collection->toArray();
        }

        return $result;
    }
}