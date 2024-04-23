<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 13:39
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id 主键
 * @property string $table_name 表名称
 * @property string $table_comment 表注释
 * @property string $module_name 所属模块
 * @property string $namespace 命名空间
 * @property string $menu_name 生成菜单名
 * @property int $belong_menu_id 所属菜单
 * @property string $package_name 控制器包名
 * @property string $type 生成类型，single 单表CRUD，tree 树表CRUD，parent_sub父子表CRUD
 * @property int $generate_type 1 压缩包下载 2 生成到模块
 * @property string $generate_menus 生成菜单列表
 * @property int $build_menu 是否构建菜单
 * @property int $component_type 组件显示方式
 * @property array $options 其他业务选项
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $remark 备注
 */
class SettingGenerateTables extends MineModel
{
    protected $table = 'setting_generate_tables';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['table_name']) && filled($params['table_name'])) {
            $query->where('table_name', 'like', '%' . $params['table_name'] . '%');
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