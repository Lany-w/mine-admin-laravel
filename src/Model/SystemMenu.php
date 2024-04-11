<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 11:17
 */

namespace Lany\MineAdmin\Model;

class SystemMenu extends CommonModel
{
    protected $table = 'system_menu';
    public const ENABLE = 1;
    public const DISABLE = 2;

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
}