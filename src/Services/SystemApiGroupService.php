<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 13:01
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemApiGroup;

class SystemApiGroupService extends SystemService
{
    public string $model = SystemApiGroup::class;

    /**
     * 获取分组列表 无分页.
     */
    public function getList(?array $params = null, bool $isScope = true): array
    {
        $params['select'] = 'id, name';
        $params['status'] = SystemApiGroup::ENABLE;
        return parent::getList($params, $isScope);
    }
}