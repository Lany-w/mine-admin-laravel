<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 10:53
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class DeptController extends MineController
{
    /**
     * Notes:前端选择树（不需要权限）.
     * User: Lany
     * DateTime: 2024/4/12 10:54
     * @param Request $request
     * @return JsonResponse
     */
    public function tree(Request $request): JsonResponse
    {
        return $this->success(app('SystemDeptService')->getSelectTree());
    }
}