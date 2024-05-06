<?php
namespace Lany\MineAdmin\Helper;
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 10:47
 */
#[\Attribute]
class Permission
{
    public static ?string $CODE;
    /**
     * @var null|string 菜单代码
     * @var string 过滤条件 为 OR 时，检查有一个通过则全部通过 为 AND 时，检查有一个不通过则全不通过
     */
    public function __construct(public ?string $code = null, public string $where = 'OR') {}
}