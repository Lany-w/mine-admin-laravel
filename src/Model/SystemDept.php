<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 11:18
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id 主键
 * @property int $parent_id 父ID
 * @property string $level 组级集合
 * @property string $name 部门名称
 * @property string $leader 负责人
 * @property string $phone 联系电话
 * @property int $status 状态 (1正常 2停用)
 * @property int $sort 排序
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemRole[] $roles
 */
class SystemDept extends MineModel
{
    use SoftDeletes;
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