<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:31
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class DictTypeController extends MineController
{
    /**
     * 获取字典列表.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemDictTypeService')->getPageList($request->all()));
    }
}