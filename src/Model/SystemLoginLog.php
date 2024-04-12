<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 17:24
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Lany\MineAdmin\Traits\PageList;

class SystemLoginLog extends MineModel
{
    use PageList;
    public $timestamps = false;
    public const SUCCESS = 1;
    public const FAIL = 2;
    protected $table = 'system_login_log';

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['ip']) && filled($params['ip'])) {
            $query->where('ip', $params['ip']);
        }

        if (isset($params['username']) && filled($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }

        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['login_time']) && filled($params['login_time']) && is_array($params['login_time']) && count($params['login_time']) == 2) {
            $query->whereBetween(
                'login_time',
                [$params['login_time'][0] . ' 00:00:00', $params['login_time'][1] . ' 23:59:59']
            );
        }
        return $query;
    }
}