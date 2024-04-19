<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 14:45
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemMenu;
use Lany\MineAdmin\Model\SystemRole;
use Lany\MineAdmin\Model\SystemUser;

class SystemMenuService
{
    /**
     * 获取树列表.
     */
    public function getTreeList(?array $params = null, bool $isScope = true): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return app(SystemMenu::class)->getTreeList($params, $isScope);
    }

    /**
     * 获取前端选择树
     */
    public function getSelectTree(array $data): array
    {
        $query = SystemMenu::query()->select(['id', 'parent_id', 'id AS value', 'name AS label'])
            ->where('status', SystemMenu::ENABLE)->orderBy('sort', 'desc');

        if (($data['scope'] ?? false) && ! user()->isSuperAdmin()) {
            $roleData = app(SystemRole::class)->getMenuIdsByRoleIds(
                SystemUser::find(user()->getId(), ['id'])->roles()->pluck('id')->toArray()
            );

            $ids = [];
            foreach ($roleData as $val) {
                foreach ($val['menus'] as $menu) {
                    $ids[] = $menu['id'];
                }
            }
            unset($roleData);
            $query->whereIn('id', array_unique($ids));
        }

        if (! empty($data['onlyMenu'])) {
            $query->where('type', SystemMenu::MENUS_LIST);
        }

        return $query->get()->toTree();
    }
}