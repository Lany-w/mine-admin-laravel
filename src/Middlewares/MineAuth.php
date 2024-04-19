<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/17 15:59
 */
namespace Lany\MineAdmin\Middlewares;
use Lany\MineAdmin\Mine;
use \Closure;
use Illuminate\Http\Request;
class MineAuth
{
    /**
     * 处理传入请求。
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Mine::guard()->check()) {
            abort(401, t('jwt.no_login'));
        }

        return $next($request);
    }
}