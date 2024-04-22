<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 17:17
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\MineModel;
use Lany\MineAdmin\Model\SystemDictData;

class SystemDictDataService extends SystemService
{
    public string $model = SystemDictData::class;
    public function getList(?array $params = null, bool $isScope = false): array
    {
        $args = [
            'select' => ['id', 'label as title', 'value as key'],
            'status' => MineModel::ENABLE,
            'orderBy' => 'sort',
            'orderType' => 'desc',
        ];

        return cache()->store('redis')->remember('system_dict_data_'. $params['code'], 600, function() use ($args, $params, $isScope) {
           return  app($this->model)->getList(array_merge($args, $params), $isScope);
        });
    }
}