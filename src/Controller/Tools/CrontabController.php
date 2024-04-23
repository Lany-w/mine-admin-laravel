<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:12
 */

namespace Lany\MineAdmin\Controller\Tools;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SettingCrontabService;

class CrontabController extends MineController
{

    /**
     * 计划任务服务
     */
    protected SettingCrontabService $service;

    /**
     * 获取列表分页数据.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }
}