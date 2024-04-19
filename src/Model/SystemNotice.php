<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 12:46
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lany\MineAdmin\Traits\PageList;

class SystemNotice extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_notice';


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['title']) && filled($params['title'])) {
            $query->where('title', '=', $params['title']);
        }

        if (isset($params['type']) && filled($params['type'])) {
            $query->where('type', '=', $params['type']);
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