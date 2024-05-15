<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/29 16:47
 */

namespace Lany\MineAdmin\Listeners;

use Lany\MineAdmin\Events\OperationLog;
use Lany\MineAdmin\Helper\Ip2region;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Model\SystemMenu;
use Lany\MineAdmin\Model\SystemOperLog;
use Lany\MineAdmin\Services\SystemMenuService;

class OperationLogListener
{
    private Ip2region $ip2region;
    public function __construct()
    {
        $this->ip2region = new Ip2region();
    }

    public function handle(OperationLog $event): void
    {
        //$data = $event->data;
        $response = $event->response;
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
            'ip_location' => $this->ip2region->search(request()->ip()),
            'service_name' => $this->getOperationMenuName(),
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

    protected function getOperationMenuName(): string
    {
        return app(SystemMenuService::class)->findNameByCode(Permission::$CODE);
    }
}