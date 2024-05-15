<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/5/14 17:09
 */

namespace Lany\MineAdmin\Middlewares;

class DeleteFileAfterDownload
{
    public static string $filePath;
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // 在响应发送后执行删除文件操作
        if ($response->getStatusCode() === 200) {
            @unlink(self::$filePath);
            self::$filePath = '';
        }
    }
}