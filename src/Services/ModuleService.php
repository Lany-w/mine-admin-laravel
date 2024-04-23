<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 13:06
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Mine;

class ModuleService extends SystemService
{
    private Mine $mine;
    public function __construct()
    {
        $this->mine = new Mine();
        $this->setModuleCache();
    }

    /**
     * 获取表状态分页列表.
     */
    public function getPageList(?array $params = [], bool $isScope = true): array
    {
        return $this->getArrayToPageList($params);
    }

    /**
     * 缓存模块信息.
     * @param null|string $moduleName 模块名
     * @param array $data 模块数据
     */
    public function setModuleCache(?string $moduleName = null, array $data = []): void
    {
        $key = 'modules';
        //$this->mine->scanModule();
        $modules = $this->mine->getModuleInfo();
        if (! empty($moduleName)) {
            $modules[$moduleName] = $data;
        }
        redis()->set($key, serialize($modules));
    }
}