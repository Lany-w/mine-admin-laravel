<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 10:36
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lany\MineAdmin\Traits\PageList;

class SystemUploadFile extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_uploadfile';

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['storage_mode']) && filled($params['storage_mode'])) {
            $query->where('storage_mode', $params['storage_mode']);
        }
        if (isset($params['origin_name']) && filled($params['origin_name'])) {
            $query->where('origin_name', 'like', '%' . $params['origin_name'] . '%');
        }
        if (isset($params['storage_path']) && filled($params['storage_path'])) {
            $query->where('storage_path', 'like', $params['storage_path'] . '%');
        }
        if (isset($params['mime_type']) && filled($params['mime_type'])) {
            $query->where('mime_type', 'like', $params['mime_type'] . '/%');
        }
        if (isset($params['minDate']) && filled($params['minDate']) && isset($params['maxDate']) && filled($params['maxDate'])) {
            $query->whereBetween(
                'created_at',
                [$params['minDate'] . ' 00:00:00', $params['maxDate'] . ' 23:59:59']
            );
        }
        return $query;
    }
}