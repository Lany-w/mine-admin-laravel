<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 10:39
 */

namespace Lany\MineAdmin\Middlewares;
use Lany\MineAdmin\Exceptions\NoPermissionException;
use Lany\MineAdmin\Helper\Permission;
use Lany\MineAdmin\Mine;
use \Closure;
use Illuminate\Http\Request;
use Lany\MineAdmin\Services\PermissionService;
use Lany\MineAdmin\Services\SystemMenuService;
use Lany\MineAdmin\Services\SystemUserService;

class MinePermission
{
    /**
     * 处理传入请求。
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Mine::guard()->user();

        $permission = app(PermissionService::class)->getPermissionAnnotation();

        if ($user->isSuperAdmin()) {
            Permission::$CODE = $permission->code;
            return $next($request);
        }
        $this->checkPermission($permission->code, $permission->where);

        return $next($request);
    }
    protected function checkPermission(string $codeString, string $where): bool
    {
        $codes = app(SystemUserService::class)->getInfo()['codes'];

        if (preg_match_all('#{(.*?)}#U', $codeString, $matches)) {
            if (isset($matches[1])) {
                foreach ($matches[1] as $name) {
                    $codeString = str_replace('{' . $name . '}', request()->route($name), $codeString);
                }
            }
        }

        if ($where === 'OR') {
            foreach (explode(',', $codeString) as $code) {
                if (in_array(trim($code), $codes)) {
                    return true;
                }
            }
            throw new NoPermissionException(
                t('system.no_permission') . ' -> [ ' . request()->getRequestUri() . ' ]'
            );
        }

        if ($where === 'AND') {
            foreach (explode(',', $codeString) as $code) {
                $code = trim($code);
                if (! in_array($code, $codes)) {
                    $service = app(SystemMenuService::class);
                    throw new NoPermissionException(
                        t('system.no_permission') . ' -> [ ' . $service->findNameByCode($code) . ' ]'
                    );
                }
            }
        }

        return true;
    }
}