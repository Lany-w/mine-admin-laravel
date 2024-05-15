<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/30 17:17
 */

namespace Lany\MineAdmin\Middlewares;
use Illuminate\Http\Request;
use \Closure;
use Lany\MineAdmin\Events\OperationLog as OperationLogEvent;
class OperationLog
{
    public static bool $FLAG = false;
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        OperationLogEvent::dispatchIf(self::$FLAG, $response);
        self::$FLAG = false;

        return $response;
    }
}