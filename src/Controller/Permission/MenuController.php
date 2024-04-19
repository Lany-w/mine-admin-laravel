<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 14:43
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class MenuController extends MineController
{
    /**
     * Notes:菜单树列表
     * User: Lany
     * DateTime: 2024/4/19 14:45
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemMenuService')->getTreeList($request->all()));
    }

    /**
     * 前端选择树.
     */
    public function tree(Request $request): JsonResponse
    {
        return $this->success(app('SystemMenuService')->getSelectTree($request->all()));
    }
}