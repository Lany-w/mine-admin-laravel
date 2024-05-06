<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/29 14:09
 */

namespace Lany\MineAdmin\Requests;

class SystemRoleRequest extends MineRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|max:30',
            'code' => 'required|min:3|max:100',
        ];
    }

    /**
     * 字段映射名称
     * return array.
     */
    public function attributes(): array
    {
        return [
            'id' => '角色ID',
            'name' => '角色名称',
            'code' => '角色标识',
            'status' => '角色状态',
        ];
    }
}