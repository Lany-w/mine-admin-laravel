<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:29
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MineModel extends Model
{
    public const ENABLE = 1;
    public const DISABLE = 2;
    protected $guarded = [];


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['code']) && filled($params['code'])) {
            $query->where('code', $params['code']);
        }

        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['filterAdminRole']) && filled($params['filterAdminRole'])) {
            $query->whereNotIn('id', [env('ADMIN_ROLE')]);
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