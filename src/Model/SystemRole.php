<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 13:31
 */

namespace Lany\MineAdmin\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemRole extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_role';

    // 所有
    public const ALL_SCOPE = 1;

    // 自定义
    public const CUSTOM_SCOPE = 2;

    // 本部门
    public const SELF_DEPT_SCOPE = 3;

    // 本部门及子部门
    public const DEPT_BELOW_SCOPE = 4;

    // 本人
    public const SELF_SCOPE = 5;

    // 本部门及子部门，通过表的部门id
    public const DEPT_BELOW_SCOPE_BY_TABLE_DEPTID = 6;

    /**
     * 通过角色ID列表获取菜单ID.
     */
    public function getMenuIdsByRoleIds(array $ids):array
    {
        if (empty($ids)) {
            return [];
        }

        return self::query()->whereIn('id', $ids)->with(['menus' => function ($query) {
            $query->select('id')->where('status', SystemMenu::ENABLE)->orderBy('sort', 'desc');
        }])->get(['id'])->toArray();
    }

    /**
     * 通过中间表获取菜单.
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(SystemMenu::class, 'system_role_menu', 'role_id', 'menu_id');
    }

    /**
     * 通过中间表获取部门.
     */
    public function depts(): BelongsToMany
    {
        return $this->belongsToMany(SystemDept::class, 'system_role_dept', 'role_id', 'dept_id');
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['code']) && filled($params['code'])) {
            $query->where('code', $params['code']);
        }

        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['filterAdminRole']) && filled($params['filterAdminRole'])) {
            $query->whereNotIn('id', [env('ADMIN_ROLE')]);
        }

        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }
        return $query;
    }
}