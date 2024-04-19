<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 11:18
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lany\MineAdmin\Traits\PageList;
use Lany\MineAdmin\Traits\UserDataScope;

class SystemDept extends MineModel
{
    use SoftDeletes;
    use PageList;
    protected $table = 'system_dept';

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (isset($params['leader']) && filled($params['leader'])) {
            $query->where('leader', $params['leader']);
        }

        if (isset($params['phone']) && filled($params['phone'])) {
            $query->where('phone', $params['phone']);
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