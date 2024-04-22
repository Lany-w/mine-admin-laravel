<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 17:21
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class DataMaintainController extends MineController
{
    /**
     * 列表.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('DataMaintainService')->getPageList($request->all()));
    }
}