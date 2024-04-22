<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 11:00
 */

namespace Lany\MineAdmin\Controller\App;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemAppService;

class SystemAppController extends MineController
{
    protected SystemAppService $service;

    /**
     * 列表.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }
}