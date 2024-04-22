<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 17:06
 */

namespace Lany\MineAdmin\Controller\Monitor;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\CacheMonitorService;

class CacheMonitorController extends MineController
{
    protected CacheMonitorService $service;

    /**
     * 获取Redis服务器信息.
     */
    public function getCacheInfo(): JsonResponse
    {
        return $this->success($this->service->getCacheServerInfo());
    }
}