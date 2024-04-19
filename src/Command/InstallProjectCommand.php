<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/8 14:50
 */

namespace Lany\MineAdmin\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use JetBrains\PhpStorm\NoReturn;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Traits\Confirmable;

class InstallProjectCommand extends MineCommand
{
    use Confirmable;
    protected const CONSOLE_GREEN_BEGIN = "\033[32;5;1m";

    protected const CONSOLE_RED_BEGIN = "\033[31;5;1m";
    protected const CONSOLE_END = "\033[0m";


    protected array $seeders = [
        \Lany\MineAdmin\Seeders\SettingConfigGroupSeeder::class,
        \Lany\MineAdmin\Seeders\SettingConfigSeeder::class,
        \Lany\MineAdmin\Seeders\SettingCrontabSeeder::class,
        \Lany\MineAdmin\Seeders\SystemDeptSeeder::class,
        \Lany\MineAdmin\Seeders\SystemDictDataSeeder::class,
        \Lany\MineAdmin\Seeders\SystemDictTypeSeeder::class,
        \Lany\MineAdmin\Seeders\SystemMenuSeeder::class,
    ];

    /**
     * 控制台命令的名称和签名
     *
     * @var string
     */
    protected $signature = 'mine:install
                            {--o|option= : input "--option=reset" is re install MineAdmin}';
    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = 'MineAdmin system install command';

    protected function configure(): void
    {
        $this->setHelp('run "php artisan mine:install" install MineAdmin system');
    }

    /**
     * 执行命令
     */
    #[NoReturn] public function handle(): void
    {
        $option = $this->option('option');
        if (!$option) {
            if (!file_exists(base_path(). '/.env')) {
                $this->error('Please set the. env file first');
                exit;
            }
            $this->welcome();
            $this->checkEnv();
            if (!$this->confirm('Do you want to continue with the installation program?', true)) {
                exit;
            }
            $this->installLocalModule();
            $this->setOthers();
            $this->finish();
        }

        // 重新安装
        if ($option === 'reset') {
            $this->line('Reinstallation is not complete...', 'error');
        }
    }

    protected function welcome(): void
    {
        $this->line('-----------------------------------------------------------', 'comment');
        $this->line('Hello, welcome use MineAdmin system.', 'comment');
        $this->line('The installation is about to start, just a few steps', 'comment');
        $this->line('-----------------------------------------------------------', 'comment');
    }

    protected function checkEnv(): void
    {
        $answer = $this->confirm('Do you want to test the system environment now?', true);

        if ($answer) {
            $this->line(PHP_EOL . ' Checking environmenting...' . PHP_EOL, 'comment');

            if (version_compare(PHP_VERSION, '8.0', '<')) {
                $this->line(sprintf(' php version should >= 8.0 >>> %sNO!%s', self::CONSOLE_RED_BEGIN, self::CONSOLE_END));
                exit;
            }
            $this->line(sprintf(' php version %s >>> %sOK!%s', PHP_VERSION, self::CONSOLE_GREEN_BEGIN, self::CONSOLE_END));

            $this->checkDb();
            $this->checkRedis();
        }
    }

    protected function checkDb(): void
    {
        if (!DB::connection()->getPdo()) {
            $this->line(sprintf(' Database link >>> %sNO!%s', self::CONSOLE_RED_BEGIN, self::CONSOLE_END));
            exit;
        }
        $this->line(sprintf(' Database link >>> %sOK!%s', self::CONSOLE_GREEN_BEGIN, self::CONSOLE_END));
    }

    protected function checkRedis(): void
    {
        try {

            if (!extension_loaded('redis')) {
                $this->warn('请先安装redis扩展');
                exit;
            }

            if (!Redis::ping()) {
                throw new \Exception('请正确设置redis');
            }
            $this->line(sprintf(' Redis link >>> %sOK!%s', self::CONSOLE_GREEN_BEGIN, self::CONSOLE_END));
        }catch (\Exception $e) {
            $this->line(sprintf(' Redis link >>> %sNO!%s', self::CONSOLE_RED_BEGIN, self::CONSOLE_END));
            exit;
        }
    }

    protected function installLocalModule(): void
    {
        $this->line("Installation of local modules is about to begin...\n", 'comment');
        if (! $this->confirmToProceed()) {
            return;
        }
        /*$mine = app(Mine::class);
        $modules = $mine->getModuleInfo();
        foreach ($modules as $name => $info) {
            $this->call('mine:migrate-run', ['name' => $name, '--force' => 'true']);
            if ($name === 'System') {
                $this->initUserData();
            }
            $this->call('mine:seeder-run', ['name' => $name, '--force' => 'true']);
            $this->line($this->getGreenText(sprintf('"%s" module install successfully', $name)));
        }*/
        $this->call('migrate');
        $this->initUserData();
        $this->seeder();
        $this->line($this->getGreenText('database migrate successfully'));
    }

    protected function setOthers(): void
    {
        $this->line(PHP_EOL . ' MineAdmin set others items...' . PHP_EOL, 'comment');

        Artisan::call('vendor:publish', [
            '--provider' => 'Tymon\JWTAuth\Providers\LaravelServiceProvider'
        ]);
        $this->call('jwt:secret');
        $this->line($this->getGreenText('jwt init successfully'));
        $downloadFrontCode = $this->confirm('Do you downloading the front-end code to "./web" directory?', true);

        // 下载前端代码
        if ($downloadFrontCode) {
            $this->line(PHP_EOL . ' Now about to start downloading the front-end code' . PHP_EOL, 'comment');
            if (\shell_exec('which git')) {
                \system('git clone git@github.com:Lany-w/MineAdmin-Vue.git ./web/');
            } else {
                $this->warn('Your server does not have the `git` command installed and will skip downloading the front-end project');
            }
        }
    }

    protected function seeder(): void
    {
        spl_autoload_register(function ($class) {
            $files = glob(__DIR__.'/../Seeders/*');
            foreach($files as $file) {
                require_once $file;
            }
        });

        foreach($this->seeders as $seed) {
            $this->call('db:seed', ['class' => $seed]);
        }
    }

    protected function initUserData(): void
    {
        // 清理数据
        Db::table('system_user')->truncate();
        Db::table('system_role')->truncate();
        Db::table('system_user_role')->truncate();
        if (Schema::hasTable('system_user_dept')) {
            Db::table('system_user_dept')->truncate();
        }

        // 创建超级管理员
        Db::table('system_user')->insert([
            'id' => env('SUPER_ADMIN', 1),
            'username' => 'superAdmin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'user_type' => '100',
            'nickname' => '创始人',
            'email' => 'admin@adminmine.com',
            'phone' => '16858888988',
            'signed' => '广阔天地，大有所为',
            'dashboard' => 'statistics',
            'created_by' => 0,
            'updated_by' => 0,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        // 创建管理员角色
        Db::table('system_role')->insert([
            'id' => env('ADMIN_ROLE', 1),
            'name' => '超级管理员（创始人）',
            'code' => 'superAdmin',
            'data_scope' => 0,
            'sort' => 0,
            'created_by' => env('SUPER_ADMIN', 0),
            'updated_by' => 0,
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'remark' => '系统内置角色，不可删除',
        ]);
        if (env('DB_DRIVER') == 'pgsql') {
            Db::select("SELECT setval('system_user_id_seq', 1)");
            Db::select("SELECT setval('system_role_id_seq', 1)");
        }
        Db::table('system_user_role')->insert([
            'user_id' => env('SUPER_ADMIN', 1),
            'role_id' => env('ADMIN_ROLE', 1),
        ]);
    }

    protected function finish(): void
    {
        $i = 5;
        $this->output->write(PHP_EOL . $this->getGreenText('The installation is almost complete'), false);
        while ($i > 0) {
            $this->output->write($this->getGreenText('.'), false);
            --$i;
            sleep(1);
        }
        $this->line(PHP_EOL . sprintf('%s
MineAdmin Version: %s
default username: superAdmin
default password: admin123', $this->getInfo(), Mine::getVersion()), 'comment');
    }

}