<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 13:36
 */

namespace Lany\MineAdmin\Controller\Tools;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SettingDatasourceService;
use Lany\MineAdmin\Services\SettingGenerateTablesService;

class GenerateCodeController extends MineController
{
    /**
     * 信息表服务
     */
    protected SettingGenerateTablesService $tableService;

    /**
     * 数据源处理服务
     * SettingDatasourceService.
     */
    protected SettingDatasourceService $datasourceService;

    /**
     * 代码生成列表分页.
     */
    public function index(): JsonResponse
    {
        return $this->success($this->tableService->getPageList($this->request->All()));
    }

    /**
     * 获取数据源列表.
     */
    public function getDataSourceList(): JsonResponse
    {
        return $this->success($this->datasourceService->getPageList([
            'select' => 'id as value, source_name as label',
        ]));
    }
}