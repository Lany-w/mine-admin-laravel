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
use Lany\MineAdmin\Events\OperationLog;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Events\UserLoginBefore;
use Lany\MineAdmin\Helper\MineCollection;
use Lany\MineAdmin\Helper\MineUpload;
use Lany\MineAdmin\Listeners\OperationLogListener;
use Lany\MineAdmin\Listeners\UserLoginAfterListener;
use Lany\MineAdmin\Listeners\UserLoginBeforeListener;
use Lany\MineAdmin\Middlewares\MineAuth;
use Lany\MineAdmin\Middlewares\MinePermission;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    private array $middlewareAliases = [
        'mine.auth' => MineAuth::class,
        'mine.permission' => MinePermission::class,
        'mine.oper.log' => \Lany\MineAdmin\Middlewares\OperationLog::class
    ];

    private array $commands = [
        InstallProjectCommand::class
    ];
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/jwt.php', 'jwt');
        $this->loadAdminAuthConfig();
        $this->registerCommand();
        $this->registerServices();
    }
    public function boot(): void
    {
        $this->aliasMiddleware();
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
            $this->commands($this->commands);
        }
    }

    protected function registerServices(): void
    {
        $this->app->singleton(MineUpload::class, fn() =>  new MineUpload());

        $files = glob(__DIR__.'/services/*');

        foreach($files as $file) {
            $fileName = pathinfo($file,PATHINFO_FILENAME);
            $class = __NAMESPACE__ . "\\Services\\$fileName";
            $this->app->singleton($class, fn() =>  new $class());
            $this->app->alias($class, $fileName);
        }

    }

    protected function registerEvent(): void
    {
        Event::listen(UserLoginBefore::class, UserLoginBeforeListener::class);
        Event::listen(UserLoginAfter::class, UserLoginAfterListener::class);
        Event::listen(OperationLog::class, OperationLogListener::class);

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

    protected function aliasMiddleware(): void
    {
        $router = $this->app->make('router');

        // register route middleware.
        foreach ($this->middlewareAliases as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }
}