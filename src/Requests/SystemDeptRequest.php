<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/31 15:53
 */

namespace Lany\MineAdmin\Requests;

class SystemDeptRequest extends MineRequest
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
            'name' => 'required|max:30',
        ];
    }

    /**
     * 新增部门领导验证规则
     * return array.
     */
    public function addLeaderRules(): array
    {
        return [
            'id' => 'required',
            'users' => 'required',
        ];
    }

    /**
     * 删除部门领导验证规则
     * return array.
     */
    public function delLeaderRules(): array
    {
        return [
            'id' => 'required',
            'ids' => 'array',
        ];
    }

    /**
     * 更新数据验证规则
     * return array.
     */
    public function updateRules(): array
    {
        return [
            'name' => 'required|max:30',
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
     * 字段映射名称
     * return array.
     */
    public function attributes(): array
    {
        return [
            'id' => '部门ID',
            'name' => '部门名称',
            'status' => '部门状态',
            'users' => '用户信息',
        ];
    }
}