<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 12:56
 */

namespace Lany\MineAdmin\Controller\Api;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemApiGroupService;

class SystemApiGroupController extends MineController
{
    protected SystemApiGroupService $service;

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
        return $this->success($this->service->getList($this->request->all()));
    }
}