<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 11:02
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id 主键
 * @property int $group_id 应用组ID
 * @property string $app_name 应用名称
 * @property string $app_id 应用ID
 * @property string $app_secret 应用密钥
 * @property int $status 状态 (1正常 2停用)
 * @property string $description 应用介绍
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemApi[] $apis
 * @property SystemAppGroup $appGroup
 */
class SystemApp extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_app';

    /**
     * 通过中间表关联API.
     */
    public function apis(): BelongsToMany
    {
        return $this->belongsToMany(SystemApi::class, 'system_app_api', 'app_id', 'api_id');
    }

    /**
     * 关联APP分组.
     */
    public function appGroup(): HasOne
    {
        return $this->hasOne(SystemAppGroup::class, 'id', 'group_id');
    }

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['app_name']) && filled($params['app_name'])) {
            $query->where('app_name', $params['app_name']);
        }

        if (isset($params['app_id']) && filled($params['app_id'])) {
            $query->where('app_id', $params['app_id']);
        }

        if (isset($params['group_id']) && filled($params['group_id'])) {
            $query->where('group_id', $params['group_id']);
        }

        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }
        return $query;
    }
}