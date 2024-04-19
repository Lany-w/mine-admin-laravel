<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 16:50
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class UserController extends MineController
{
    /**
     * Notes:用户列表
     * User: Lany
     * DateTime: 2024/4/12 16:51
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemUserService')->getPageList($request->all(), false));
    }
}