<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:54
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemRole;

class SystemRoleService extends SystemService
{
    public string $model = SystemRole::class;
    public bool $filterAdminRole = true;
}