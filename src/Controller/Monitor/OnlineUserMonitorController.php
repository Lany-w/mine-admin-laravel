<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 15:24
 */

namespace Lany\MineAdmin\Controller\Monitor;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemUserService;

class OnlineUserMonitorController extends MineController
{
    protected SystemUserService $service;

    /**
     * 获取在线用户列表.
     */
    public function getPageList(): JsonResponse
    {
        return $this->success($this->service->getOnlineUserPageList($this->request->all()));
    }
}