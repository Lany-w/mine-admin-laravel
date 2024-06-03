<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 11:17
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
 * @property string $name 菜单名称
 * @property string $code 菜单标识代码
 * @property string $icon 菜单图标
 * @property string $route 路由地址
 * @property string $component 组件路径
 * @property string $redirect 跳转地址
 * @property int $is_hidden 是否隐藏 (1是 2否)
 * @property string $type 菜单类型, (M菜单 B按钮 L链接 I iframe)
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
class SystemMenu extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_menu';
    public const LINK = 'L';

    public const IFRAME = 'I';

    public const MENUS_LIST = 'M';

    public const BUTTON = 'B';


    /**
     * 查询的菜单字段.
     */
    protected array $menuField = [
        'id',
        'parent_id',
        'name',
        'code',
        'icon',
        'route',
        'is_hidden',
        'component',
        'redirect',
        'type',
    ];

    /**
     * Notes:获取超级管理员（创始人）的路由菜单
     * User: Lany
     * DateTime: 2024/4/10 11:19
     * @return array
     */
    public function getSuperAdminRouters()
    {
        return self::query()
            ->select($this->menuField)
            ->where('status', self::ENABLE)
            ->orderBy('sort', 'desc')
            ->get()->sysMenuToRouterTree();
    }

    /**
     * 通过菜单ID列表获取菜单数据.
     */
    public function getRoutersByIds(array $ids): array
    {
        return self::query()
            ->select($this->menuField)
            ->whereIn('id', $ids)
            ->where('status', self::ENABLE)
            ->orderBy('sort', 'desc')
            ->get()->sysMenuToRouterTree();
    }

    /**
     * 查询菜单code.
     */
    public function getMenuCode(?array $ids = null): array
    {
        return self::query()->whereIn('id', $ids)->pluck('code')->toArray();
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }

        if (isset($params['noButton']) && filled($params['noButton']) && $params['noButton'] === true) {
            $query->where('type', '<>', self::BUTTON);
        }
        return $query;
    }
}