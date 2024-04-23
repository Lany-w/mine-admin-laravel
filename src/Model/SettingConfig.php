<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:25
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $group_id 组id
 * @property string $key 配置键名
 * @property string $value 配置值
 * @property string $name 配置名称
 * @property string $input_type 数据输入类型
 * @property string $config_select_data 配置选项数据
 * @property int $sort 排序
 * @property string $remark 备注
 */
class SettingConfig extends MineModel
{

    public $incrementing = false;

    public $timestamps = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    protected $table = 'setting_config';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['group_id']) && filled($params['group_id'])) {
            $query->where('group_id', $params['group_id']);
        }
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', $params['name']);
        }
        if (isset($params['key']) && filled($params['key'])) {
            $query->where('key', 'like', '%' . $params['key'] . '%');
        }
        return $query;
    }
}