<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 13:21
 */

namespace Lany\MineAdmin\Controller\Monitor;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\ServerMonitorService;

class ServerMonitorController extends MineController
{
    protected ServerMonitorService $service;

    /**
     * 获取服务器信息.
     */
    public function getServerInfo(): JsonResponse
    {
        if (is_in_container()) {
            return $this->error(t('system.monitor_server_in_container'));
        }
        return $this->success([
            'cpu' => $this->service->getCpuInfo(),
            'memory' => $this->service->getMemInfo(),
            'phpenv' => $this->service->getPhpAndEnvInfo(),
            'disk' => $this->service->getDiskInfo(),
        ]);
    }
}