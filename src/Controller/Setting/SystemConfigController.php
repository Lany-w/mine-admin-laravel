<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:27
 */

namespace Lany\MineAdmin\Controller\Setting;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SettingConfigService;

class SystemConfigController extends MineController
{

    protected SettingConfigService $service;

    /**
     * 获取配置列表.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getList($this->request->all()));
    }
}