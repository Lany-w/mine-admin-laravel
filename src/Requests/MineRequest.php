<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 10:54
 */
namespace Lany\MineAdmin\Requests;
class MineRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize(): true
    {
        return true;
    }
}