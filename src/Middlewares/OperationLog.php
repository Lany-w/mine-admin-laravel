<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/30 17:17
 */

namespace Lany\MineAdmin\Middlewares;
use Illuminate\Http\Request;
use \Closure;
use Illuminate\Support\Facades\Log;
use Lany\MineAdmin\Helper\Annotation\Handle\OperationLogAnnotation;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Helper\Ip2region;
use Lany\MineAdmin\Model\SystemOperLog;
use Lany\MineAdmin\Services\SystemMenuService;

class OperationLog
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @throws \ReflectionException
     */
    public function terminate($request, $response): void
    {
        $annotations = OperationLogAnnotation::getAnnotation();
        Log::channel('daily')->debug($annotations);
        Log::channel('daily')->debug($annotations instanceof \Lany\MineAdmin\Helper\Annotation\OperationLog);
        if ($annotations instanceof \Lany\MineAdmin\Helper\Annotation\OperationLog) {
            //记录操作日志
            $isDownload = false;
            if ($response->headers->has('Content-Disposition')) {
                $isDownload = true;
            }

            $operationLog = [
                //'time' => now(),
                'method' => request()->method(),
                'router' => request()->getRequestUri(),
                //'protocol' => request()->get,
                'ip' => request()->ip(),
                'ip_location' => (new Ip2region())->search(request()->ip()),
                'service_name' => Permission::$CODE ? $this->getOperationMenuName() : $annotations->menuName,
                'request_data' => json_encode(request()->all(), JSON_UNESCAPED_UNICODE),
                'response_code' => $response->getStatusCode(),
                'response_data' => $isDownload ? '文件下载' : $response->getContent(),
            ];
            try {
                $operationLog['username'] = user()->getUsername();
            } catch (\Exception $e) {
                $operationLog['username'] = t('system.no_login_user');
            }

            SystemOperLog::query()->create($operationLog);
        }

    }

    protected function getOperationMenuName(): string
    {
        return app(SystemMenuService::class)->findNameByCode(Permission::$CODE);
    }
}