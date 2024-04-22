<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 10:08
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemAppGroup;

class SystemAppGroupService extends SystemService
{
    public string $model = SystemAppGroup::class;

    /**
     * 获取分组列表 无分页.
     */
    public function getList(?array $params = null, bool $isScope = true): array
    {
        return app($this->model)->getList(['select' => ['id', 'name'], 'status' => SystemAppGroup::ENABLE], $isScope);
    }
}