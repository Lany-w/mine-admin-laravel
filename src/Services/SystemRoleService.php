<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:54
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemRole;

class SystemRoleService
{
    /**
     * 获取角色列表，并过滤掉超管角色.
     */
    public function getList(?array $params = null, bool $isScope = true): array
    {
        $params['filterAdminRole'] = true;
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return app(SystemRole::class)->getList($params, $isScope);
    }

    public function getPageList(?array $params = null, bool $isScope = true): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        return app(SystemRole::class)->getPageList($params, $isScope);
    }
}