<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 10:27
 */

namespace Lany\MineAdmin\Controller\Logs;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemApiLogService;
use Lany\MineAdmin\Services\SystemLoginLogService;
use Lany\MineAdmin\Services\SystemOperLogService;
use Lany\MineAdmin\Services\SystemQueueLogService;

class LogsController extends MineController
{
    protected SystemLoginLogService $loginLogService;
    protected SystemOperLogService $operLogService;
    protected SystemApiLogService $apiLogService;
    protected SystemQueueLogService $queueLogService;

    /**
     * 获取登录日志列表.
     */
    public function getLoginLogPageList(): JsonResponse
    {
        return $this->success($this->loginLogService->getPageList($this->request->all()));
    }

    /*
     * 获取操作日志列表
     */
    public function getOperLogPageList():JsonResponse
    {
        return $this->success($this->operLogService->getPageList($this->request->all()));
    }

    /**
     * 获取接口日志列表.
     */
    public function getApiLogPageList(): JsonResponse
    {
        return $this->success($this->apiLogService->getPageList($this->request->all()));
    }

    /**
     * 获取队列日志列表.
     */
    public function getQueueLogPageList(): JsonResponse
    {
        return $this->success($this->queueLogService->getPageList($this->request->all()));
    }
}