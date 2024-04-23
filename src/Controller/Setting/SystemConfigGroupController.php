<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:23
 */

namespace Lany\MineAdmin\Controller\Setting;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SettingConfigGroupService;

class SystemConfigGroupController extends MineController
{
    protected SettingConfigGroupService $service;

    /**
     * 获取系统组配置.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getList());
    }
}