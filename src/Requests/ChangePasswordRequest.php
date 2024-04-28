<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/26 17:00
 */

namespace Lany\MineAdmin\Requests;

use Illuminate\Support\Facades\Hash;
use Lany\MineAdmin\Model\SystemUser;


class ChangePasswordRequest extends MineRequest
{
    /**
     * 修改密码验证规则
     * return array.
     */
    public function rules(): array
    {
        return [
            'newPassword' => 'required|confirmed|string',
            'newPassword_confirmation' => 'required|string',
            'oldPassword' => ['required', 'current_password:'.config('mine_admin.auth.guard') ?: 'system'],
        ];
    }

    /**
     * 字段映射名称
     * return array.
     */
    public function attributes(): array
    {
        return [
            'oldPassword' => '旧密码',
            'newPassword' => '新密码',
            'newPassword_confirmation' => '确认密码',
        ];
    }

    public function messages(): array
    {
        return [
            'oldPassword.current_password' => t('system.valid_password')
        ];
    }
}