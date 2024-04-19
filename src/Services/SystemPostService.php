<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 14:29
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemPost;

class SystemPostService
{
    public function getList(?array $params = null, bool $isScope = true): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return app(SystemPost::class)->getList($params, $isScope);
    }
}