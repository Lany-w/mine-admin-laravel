<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 13:25
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Lany\MineAdmin\Traits\PageList;

class SystemOperLog extends MineModel
{
    use PageList;
    protected $table = 'system_oper_log';


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['ip']) && filled($params['ip'])) {
            $query->where('ip', $params['ip']);
        }
        if (isset($params['service_name']) && filled($params['service_name'])) {
            $query->where('service_name', 'like', '%' . $params['service_name'] . '%');
        }
        if (isset($params['username']) && filled($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
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