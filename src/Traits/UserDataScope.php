<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 11:12
 */

namespace Lany\MineAdmin\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Model\SystemDept;
use Lany\MineAdmin\Model\SystemRole;

trait UserDataScope
{
    public function scopeUserDataScope(Builder $query): void
    {
        if (! config('mine_admin.data_scope_enabled')) {
            return ;
        }


        $userIds = [];
        $userModel = Mine::guard()->user();
        $this->userid = $userModel->id;
        $userid = $this->userid;

        if (empty($userid)) {
            throw new MineException('Data Scope missing user_id');
        }
        if ($userid == config('mine_admin.super_admin_id')) {
            return ;
        }

        $roles = $userModel->roles()->get(['id', 'data_scope']);
        foreach ($roles as $role) {
            switch ($role->data_scope) {
                case SystemRole::ALL_SCOPE:
                    // 如果是所有权限，跳出所有循环
                    break 2;
                case SystemRole::CUSTOM_SCOPE:
                    // 自定义数据权限
                    $deptIds = $role->depts()->pluck('id')->toArray();
                    $userIds = array_merge(
                        $userIds,
                        DB::table('system_user_dept')->whereIn('dept_id', $deptIds)->pluck('user_id')->toArray()
                    );
                    $userIds[] = $this->userid;
                    break;
                case SystemRole::SELF_DEPT_SCOPE:
                    // 本部门数据权限
                    $deptIds = Db::table('system_user_dept')->where('user_id', $userModel->id)->pluck('dept_id')->toArray();
                    $userIds = array_merge(
                        $userIds,
                        Db::table('system_user_dept')->whereIn('dept_id', $deptIds)->pluck('user_id')->toArray()
                    );
                    $userIds[] = $this->userid;
                    break;
                case SystemRole::DEPT_BELOW_SCOPE:
                    // 本部门及子部门数据权限
                    $parentDepts = Db::table('system_user_dept')->where('user_id', $userModel->id)->pluck('dept_id')->toArray();
                    $ids = [];
                    foreach ($parentDepts as $deptId) {
                        $ids[] = SystemDept::query()
                            ->where(function ($query) use ($deptId) {
                                $query->where('id', '=', $deptId)
                                    ->orWhere('level', 'like', $deptId . ',%')
                                    ->orWhere('level', 'like', '%,' . $deptId)
                                    ->orWhere('level', 'like', '%,' . $deptId . ',%');
                            })
                            ->pluck('id')
                            ->toArray();
                    }
                    $deptIds = array_merge($parentDepts, ...$ids);
                    $userIds = array_merge(
                        $userIds,
                        Db::table('system_user_dept')->whereIn('dept_id', $deptIds)->pluck('user_id')->toArray()
                    );
                    $userIds[] = $this->userid;
                    break;
                case SystemRole::DEPT_BELOW_SCOPE_BY_TABLE_DEPTID:
                    $parentDepts = Db::table('system_user_dept')->where('user_id', $userModel->id)->pluck('dept_id')->toArray();
                    $ids = [];
                    foreach ($parentDepts as $deptId) {
                        $ids[] = SystemDept::query()
                            ->where(function ($query) use ($deptId) {
                                $query->where('id', '=', $deptId)
                                    ->orWhere('level', 'like', $deptId . ',%')
                                    ->orWhere('level', 'like', '%,' . $deptId)
                                    ->orWhere('level', 'like', '%,' . $deptId . ',%');
                            })
                            ->pluck('id')
                            ->toArray();
                    }
                    $deptIds = array_merge($parentDepts, ...$ids);

                    //                            // 本部门及子部门数据权限 以 当前表的dept_id作为条件
                    //                            if (! in_array('dept_id', $this->model->getFillable())) {
                    //                                break;
                    //                            }

                    $query->whereIn('dept_id', $deptIds);
                // no break
                case SystemRole::SELF_SCOPE:
                    $userIds[] = $this->userid;
                    break;
                default:
                    break;
            }
        }

         if (!empty($userIds)) {
             $query->whereIn(config('mine_admin.data_scope_field', 'created_by'), array_unique($userIds));
         }
    }
}