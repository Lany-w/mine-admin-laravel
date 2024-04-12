<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:19
 */
namespace Lany\MineAdmin\Listeners;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\NoReturn;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Events\UserLoginBefore;
use Lany\MineAdmin\Helper\Ip2region;
use Lany\MineAdmin\Model\SystemLoginLog;

class UserLoginAfterListener
{
    /**
     * 创建事件监听器
     */
    public function __construct()
    {
        // ...
    }

    /**
     * 处理事件
     */
    #[NoReturn] public function handle(UserLoginAfter $event): void
    {
        $user = $event->systemUser;
        $loginStatus = $event->loginStatus;
        $agent = request()->header('user-agent') ?? 'unknown';
        $ip = request()->ip();
        $ip2region = new Ip2region();
        SystemLoginLog::query()->create([
            'username' => $user->username,
            'ip' => $ip,
            'ip_location' => $ip2region->search($ip),
            'os' => $this->os($agent),
            'browser' => $this->browser($agent),
            'status' => $event->loginStatus ? SystemLoginLog::SUCCESS : SystemLoginLog::FAIL,
            'message' => $loginStatus === -1 ? trans('jwt.user_ban') : ($loginStatus ? trans('jwt.login_success') : trans('jwt.login_error')),
            'login_time' => date('Y-m-d H:i:s'),
        ]);
    }

    private function os($agent): string
    {
        if (stripos($agent, 'win') !== false && preg_match('/nt 6.1/i', $agent)) {
            return 'Windows 7';
        }
        if (stripos($agent, 'win') !== false && preg_match('/nt 6.2/i', $agent)) {
            return 'Windows 8';
        }
        if (stripos($agent, 'win') !== false && preg_match('/nt 10.0/i', $agent)) {
            return 'Windows 10';
        }
        if (stripos($agent, 'win') !== false && preg_match('/nt 11.0/i', $agent)) {
            return 'Windows 11';
        }
        if (stripos($agent, 'win') !== false && preg_match('/nt 5.1/i', $agent)) {
            return 'Windows XP';
        }
        if (stripos($agent, 'linux') !== false) {
            return 'Linux';
        }
        if (stripos($agent, 'mac') !== false) {
            return 'Mac';
        }
        return trans('jwt.unknown');
    }

    private function browser($agent): string
    {
        if (stripos($agent, 'MSIE') !== false) {
            return 'MSIE';
        }
        if (stripos($agent, 'Edg') !== false) {
            return 'Edge';
        }
        if (stripos($agent, 'Chrome') !== false) {
            return 'Chrome';
        }
        if (stripos($agent, 'Firefox') !== false) {
            return 'Firefox';
        }
        if (stripos($agent, 'Safari') !== false) {
            return 'Safari';
        }
        if (stripos($agent, 'Opera') !== false) {
            return 'Opera';
        }
        return trans('jwt.unknown');
    }
}