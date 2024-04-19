<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:52
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class RoleController extends MineController
{
    /**
     * Notes:角色分页列表
     * User: Lany
     * DateTime: 2024/4/19 13:34
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemRoleService')->getPageList($request->all()));
    }

    /**
     * Notes:获取角色列表 (不验证权限).
     * User: Lany
     * DateTime: 2024/4/12 13:53
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return $this->success(app('SystemRoleService')->getList());
    }
}