<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/2 17:26
 */
namespace Lany\MineAdmin;
use Illuminate\Support\Facades\Auth;
use Lany\MineAdmin\Controller\LoginController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;

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
        $attributes = [
            'prefix'     => config('mine_admin.route.prefix', 'system'),
            'middleware' => config('mine_admin.route.middleware', []),
        ];

        if (config('mine_admin.auth.enable', true)) {
            app('router')->group($attributes, function ($router) {
                $authController = config('mine_admin.auth.controller', LoginController::class);
                $router->get('/getBingBackgroundImage', $authController.'@getBingBackgroundImage');
                $router->post('/login', $authController.'@login');
                $router->get('/getInfo', $authController.'@getInfo');
            });
        }
    }

    public static function guard(): Guard|StatefulGuard
    {
        return Auth::guard(config('mine_admin.auth.guard') ?: 'system');
    }
}