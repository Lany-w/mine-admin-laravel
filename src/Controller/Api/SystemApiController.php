<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 12:55
 */

namespace Lany\MineAdmin\Controller\Api;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemApiService;

class SystemApiController extends MineController
{
    protected SystemApiService $service;

    /**
     * 列表
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }
}