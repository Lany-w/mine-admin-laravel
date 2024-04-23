<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:14
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;

class SettingCrontab extends MineModel
{
    // 命令任务
    public const COMMAND_CRONTAB = 1;

    // 类任务
    public const CLASS_CRONTAB = 2;

    // URL任务
    public const URL_CRONTAB = 3;

    // EVAL 任务
    public const EVAL_CRONTAB = 4;

    protected $table = 'setting_crontab';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }
        if (isset($params['type']) && filled($params['type'])) {
            $query->where('type', $params['type']);
        }
        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }
        return $query;
    }
}