<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/2 17:26
 */
namespace Lany\MineAdmin;
use Illuminate\Support\Facades\Auth;
use Lany\MineAdmin\Controller\CommonController;
use Lany\MineAdmin\Controller\DataCenter\DictDataController;
use Lany\MineAdmin\Controller\DataCenter\QueueMessageController;
use Lany\MineAdmin\Controller\LoginController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Lany\MineAdmin\Controller\Permission\DeptController;
use Lany\MineAdmin\Controller\Permission\RoleController;

class Mine
{
    private array $moduleInfo = [];
    protected static string $version = '0.0.1';

    public function __construct()
    {
        $this->scanModule();
    }
    public function getModuleInfo(?string $name = null): array
    {
        if (empty($name)) {
            return $this->moduleInfo;
        }
        return $this->moduleInfo[$name] ?? [];
    }

    public static function getVersion(): string
    {
        return self::$version;
    }

    public function scanModule(): void
    {
        $modules = glob(__DIR__.'/Admin/' . '*');
        $infos = [];
        foreach ($modules as &$mod) {
            if (is_dir($mod)) {
                $modInfo = $mod . DIRECTORY_SEPARATOR . 'config.json';
                if (file_exists($modInfo)) {
                    $infos[basename($mod)] = json_decode(file_get_contents($modInfo), true);
                }
            }
        }
        $sortId = array_column($infos, 'order');
        array_multisort($sortId, SORT_ASC, $infos);
        $this->setModuleInfo($infos);
    }

    public function setModuleInfo($moduleInfo): void
    {
        $this->moduleInfo = $moduleInfo;
    }

    public static function routes(): void
    {

    }

    public static function guard(): Guard|StatefulGuard
    {
        return Auth::guard(config('mine_admin.auth.guard') ?: 'system');
    }
}