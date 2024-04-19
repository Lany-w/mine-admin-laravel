<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 10:39
 */

namespace Lany\MineAdmin\Middlewares;
use Lany\MineAdmin\Mine;
use \Closure;
use Illuminate\Http\Request;
class MinePermission
{
    /**
     * 处理传入请求。
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Mine::guard()->user();

        if ($user->isSuperAdmin()) {
            $next($request);
        }

        return $next($request);
    }
}