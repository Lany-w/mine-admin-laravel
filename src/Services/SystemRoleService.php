<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:54
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Model\SystemRole;
use Lany\MineAdmin\Middlewares\OperationLog;

class SystemRoleService extends SystemService
{
    public string $model = SystemRole::class;
    public bool $filterAdminRole = true;

    public function save(array $data): mixed
    {
        if ($this->checkRoleCode($data['code'])) {
            throw new NormalStatusException(t('system.rolecode_exists'));
        }

        $menuIds = $data['menu_ids'] ?? [];
        $deptIds = $data['dept_ids'] ?? [];
        //$this->filterExecuteAttributes($data);

        $role = app($this->model)::create($data);
        empty($menuIds) || $role->menus()->sync(array_unique($menuIds), false);
        empty($deptIds) || $role->depts()->sync($deptIds, false);

        return $role->id;
    }
    /**
     * 更新角色.
     */
    public function update(mixed $id, array $data): bool
    {
        deleteCache('loginInfo*');
        $menuIds = $data['menu_ids'] ?? [];
        $deptIds = $data['dept_ids'] ?? [];
        $this->filterExecuteAttributes($data, true);
        $this->model::query()->where('id', $id)->update($data);
        if ($id != env('ADMIN_ROLE')) {
            $role = $this->model::find($id);
            if ($role) {
                ! empty($menuIds) && $role->menus()->sync(array_unique($menuIds));
                ! empty($deptIds) && $role->depts()->sync($deptIds);
                return true;
            }
        }
        return false;
    }

    /**
     * 通过角色获取菜单.
     */
    public function getMenuByRole(int $id): array
    {
        return app($this->model)->getMenuIdsByRoleIds(['ids' => $id]);
    }

    /**
     * 通过角色获取部门.
     */
    public function getDeptByRole(int $id): array
    {
        return app($this->model)->getDeptIdsByRoleIds(['ids' => $id]);
    }



    /**
     * 单个或批量软删除数据.
     */
   /* #[DeleteCache('loginInfo:*')]
    public function delete(array $ids): bool
    {
        $adminId = env('ADMIN_ROLE');
        if (in_array($adminId, $ids)) {
            unset($ids[array_search($adminId, $ids)]);
        }
        $this->model::destroy($ids);
        return true;
    }*/

    /**
     * 检查角色code是否已存在.
     */
    public function checkRoleCode(string $code): bool
    {
        return app($this->model)::query()->where('code', $code)->exists();
    }

    /**
     * 单个或批量软删除数据.
     */
    public function delete(array $ids): bool
    {
        if (empty($ids)) return true;

        $adminId = env('ADMIN_ROLE');
        if (in_array($adminId, $ids)) {
            unset($ids[array_search($adminId, $ids)]);
        }
        $this->model::destroy($ids);
        return true;
    }

}