<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 10:47
 */

namespace Lany\MineAdmin\Requests;

class SystemUserSaveRequest extends MineRequest
{
    public function rules()
    {
        return [
            'username' => 'required|max:20',
            'password' => 'required|min:6',
            'dept_ids' => 'required',
            'role_ids' => 'required',
        ];
    }

    /**
     * 字段映射名称
     * return array.
     */
    public function attributes(): array
    {
        return [
            'username' => '用户名',
            'password' => '用户密码',
            'dept_ids' => '部门ID',
            'role_ids' => '角色列表',
        ];
    }
}