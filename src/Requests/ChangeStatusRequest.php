<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/6 14:38
 */

namespace Lany\MineAdmin\Requests;

class ChangeStatusRequest extends MineRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required',
            'status' => 'required',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => '用户ID',
            'status' => '状态',
        ];
    }
}