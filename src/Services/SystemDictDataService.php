<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 17:17
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\MineModel;
use Lany\MineAdmin\Model\SystemDictData;

class SystemDictDataService
{
    public function getList(?array $params = null, bool $isScope = false): array
    {
        $args = [
            'select' => ['id', 'label as title', 'value as key'],
            'status' => MineModel::ENABLE,
            'orderBy' => 'sort',
            'orderType' => 'desc',
        ];
        return app(SystemDictData::class)->getList(array_merge($args, $params), $isScope);
    }
}