<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/2 17:20
 */
namespace Lany\MineAdmin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Lany\MineAdmin\Command\InstallProjectCommand;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Events\UserLoginBefore;
use Lany\MineAdmin\Helper\MineCollection;
use Lany\MineAdmin\Listeners\UserLoginAfterListener;
use Lany\MineAdmin\Listeners\UserLoginBeforeListener;
use Lany\MineAdmin\Services\SystemUserService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/jwt.php', 'jwt');
        $this->loadAdminAuthConfig();
        $this->registerCommand();
        $this->registerServices();
    }
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/route/route.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'mine');
        $this->langFiles();
        $this->registerEvent();
        MineCollection::boot();
    }

    protected function registerCommand(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallProjectCommand::class
            ]);
        }
    }

    protected function registerServices(): void
    {
        $this->app->singleton(SystemUserService::class, fn() =>  new SystemUserService());
        $this->app->alias(SystemUserService::class, 'SystemUserService');
    }

    protected function registerEvent(): void
    {
        Event::listen(UserLoginBefore::class, UserLoginBeforeListener::class);
        Event::listen(UserLoginAfter::class, UserLoginAfterListener::class);

    }

    protected function langFiles(): void
    {
        if (file_exists(base_path(). '/lang')) {
            $this->publishTranslate();
        } else {
            Artisan::call('lang:publish');
            $this->publishTranslate();
        }
    }

    protected function publishTranslate(): void
    {
        $this->publishes([__DIR__.'/../config/mine_admin.php' => config_path('mine_admin.php'),]);
        $this->publishes([__DIR__.'/lang/zh_CN/' => base_path().'/lang/zh_CN']);
        $this->publishes([__DIR__.'/lang/en/' => base_path().'/lang/en']);
    }

    protected function loadAdminAuthConfig(): void
    {
        config(Arr::dot(config('mine_admin.auth', []), 'auth.'));
    }
}