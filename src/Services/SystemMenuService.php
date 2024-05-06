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

class SystemMenuService extends SystemService
{
    public string $model = SystemMenu::class;

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

    /**
     * 通过code获取菜单名称.
     */
    public function findNameByCode(string $code): string
    {
        if (strlen($code) < 1) {
            return t('system.undefined_menu');
        }
        $name = app($this->model)::query()->where('code', $code)->value('name');
        return $name ?? t('system.undefined_menu');
    }
}