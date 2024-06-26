<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 10:03
 */

namespace Lany\MineAdmin\Controller\App;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemAppGroupService;

class SystemAppGroupController extends MineController
{
    protected SystemAppGroupService $service;

    /**
     * 列表.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }

    /**
     * 列表，无分页.
     */
    public function list(): JsonResponse
    {
        return $this->success($this->service->getList());
    }
}