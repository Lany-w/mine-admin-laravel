<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 15:00
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id 主键
 * @property int $type_id 字典类型ID
 * @property string $label 字典标签
 * @property string $value 字典值
 * @property string $code 字典标示
 * @property int $sort 排序
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemDictData extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_dict_data';

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['type_id']) && filled($params['type_id'])) {
            $query->where('type_id', $params['type_id']);
        }
        if (isset($params['code']) && filled($params['code'])) {
            $query->where('code', $params['code']);
        }
        if (isset($params['value']) && filled($params['value'])) {
            $query->where('value', 'like', '%' . $params['value'] . '%');
        }
        if (isset($params['label']) && filled($params['label'])) {
            $query->where('label', 'like', '%' . $params['label'] . '%');
        }
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        return $query;
    }
}