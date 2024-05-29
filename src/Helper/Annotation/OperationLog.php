<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/21 11:12
 */

namespace Lany\MineAdmin\Helper\Annotation;

#[\Attribute]
class OperationLog extends AbstractAnnotation
{

    public function __construct(public ?string $menuName = null) {}
}