<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 10:53
 */
namespace Lany\MineAdmin\Requests;

class LoginRequest extends MineRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|max:20',
            'password' => 'required|min:6',
        ];
    }

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