<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 14:28
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class PostController extends MineController
{
    /**
     * Notes:获取岗位列表
     * User: Lany
     * DateTime: 2024/4/12 14:29
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return $this->success(app('SystemPostService')->getList());
    }
}