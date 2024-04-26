<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 16:32
 */

namespace Lany\MineAdmin\Requests;

use Lany\MineAdmin\Services\SettingConfigService;

class UploadImageRequest extends MineRequest
{
    public function rules()
    {
        return [
            'image' => 'required|mimes:' . $this->getMimes('upload_allow_image'),
            'path' => 'max:30',
        ];
    }

    /**
     * 获取Mimes.
     * @param mixed $key
     * @return string
     */
    protected function getMimes(mixed $key): string
    {
        return app(SettingConfigService::class)->getConfigByKey($key)['value'] ?? '';
    }
}