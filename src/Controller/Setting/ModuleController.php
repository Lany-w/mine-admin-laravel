<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 13:05
 */

namespace Lany\MineAdmin\Controller\Setting;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\ModuleService;

class ModuleController extends MineController
{
    protected ModuleService $service;

    /**
     * 本地模块列表.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }
}