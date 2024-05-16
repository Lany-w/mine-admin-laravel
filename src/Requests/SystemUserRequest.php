<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/15 13:27
 */

namespace Lany\MineAdmin\Requests;

class SystemUserRequest extends MineRequest
{
    /**
     * 公共规则.
     */
    public function commonRules(): array
    {
        return [];
    }

    /**
     * 新增数据验证规则
     * return array.
     */
    public function saveRules(): array
    {
        return [
            'username' => 'required|max:20',
            'password' => 'required|min:6',
            'dept_ids' => 'required',
            'role_ids' => 'required',
        ];
    }

    /**
     * 新增数据验证规则
     * return array.
     */
    public function updateRules(): array
    {
        return [
            'username' => 'required|max:20',
            'dept_ids' => 'required',
            'role_ids' => 'required',
        ];
    }

    /**
     * 修改状态数据验证规则
     * return array.
     */
    public function changeStatusRules(): array
    {
        return [
            'id' => 'required',
            'status' => 'required',
        ];
    }


    /**
     * 设置用户首页数据验证规则.
     */
    public function setHomePageRules(): array
    {
        return [
            'id' => 'required',
            'dashboard' => 'required',
        ];
    }

    /**
     * 登录规则.
     * @return string[]
     */
    public function loginRules(): array
    {
        return [
            'username' => 'required|max:20',
            'password' => 'required|min:6',
        ];
    }

    /**
     * 字段映射名称
     * return array.
     */
    public function attributes(): array
    {
        return [
            'id' => '用户ID',
            'username' => '用户名',
            'password' => '用户密码',
            'dashboard' => '用户后台首页',
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'newPassword_confirmation' => '确认密码',
            'status' => '用户状态',
            'dept_ids' => '部门ID',
            'role_ids' => '角色列表',
        ];
    }
}