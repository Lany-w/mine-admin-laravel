<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 14:28
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SettingConfig;

class SettingConfigService extends SystemService
{
    public string $model = SettingConfig::class;

    /**
     * 按key获取配置，并缓存.
     */
    public function getConfigByKey(string $key): ?array
    {
        return cache()->store('redis')->remember('system:config:value_'. $key, 600, function() use ($key) {
            return app($this->model)->getConfigByKey($key);
        });

    }
}