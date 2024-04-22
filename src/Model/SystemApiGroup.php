<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 13:03
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id 主键
 * @property string $name 接口组名称
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemApi[] $apis
 */
class SystemApiGroup extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_api_group';

    /**
     * 关联API.
     */
    public function apis(): HasMany
    {
        return $this->hasMany(SystemApi::class, 'group_id', 'id');
    }

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        // 应用组名称
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', '=', $params['name']);
        }

        // 状态
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', '=', $params['status']);
        }

        // 关联查询api列表
        if (isset($params['getApiList']) && filled($params['getApiList']) && $params['getApiList'] == true) {
            $query->with(['apis' => function ($query) {
                $query->where('status', SystemApi::ENABLE)->select(['id', 'group_id', 'name', 'access_name']);
            }]);
        }
        return $query;
    }
}