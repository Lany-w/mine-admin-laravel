<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 13:05
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id 主键
 * @property int $api_id 接口主键
 * @property string $name 字段名称
 * @property int $type 字段类型 1 请求 2 返回
 * @property string $data_type 数据类型
 * @property int $is_required 是否必填 1 非必填 2 必填
 * @property string $default_value 默认值
 * @property int $status 状态 (1正常 2停用)
 * @property string $description 字段说明
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property SystemApi $api
 */
class SystemApiColumn extends MineModel
{
    use SoftDeletes;

    protected $table = 'system_api_column';

    /**
     * 关联API.
     */
    public function api(): BelongsTo
    {
        return $this->belongsTo(SystemApi::class, 'api_id', 'id');
    }

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        // 接口ID
        if (isset($params['api_id']) && filled($params['api_id'])) {
            $query->where('api_id', '=', $params['api_id']);
        }

        // 字段类型
        if (isset($params['type']) && filled($params['type'])) {
            $query->where('type', '=', $params['type']);
        }

        // 字段名称
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', '=', $params['name']);
        }

        // 数据类型
        if (isset($params['data_type']) && filled($params['data_type'])) {
            $query->where('data_type', '=', $params['data_type']);
        }

        // 是否必填 1 非必填 2 必填
        if (isset($params['is_required']) && filled($params['is_required'])) {
            $query->where('is_required', '=', $params['is_required']);
        }

        // 状态 (1正常 2停用)
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', '=', $params['status']);
        }
        return $query;
    }
}