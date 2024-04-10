<?php
namespace Lany\MineAdmin\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingCrontabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Db::table('setting_crontab')->truncate();
        foreach ($this->getData() as $item) {
            Db::insert($item);
        }
    }

    public function getData(): array
    {
        $tableName = 'setting_crontab';
        if (env('DB_CONNECTION') == 'pgsql') {
            Db::select("SELECT setval('{$tableName}_id_seq', 2)");
            return [
                "INSERT INTO \"{$tableName}\" VALUES (1, 'urlCrontab', '3', 'http://127.0.0.1:9501/', '', '59 */1 * * * *', 2, 2, NULL, NULL, '2021-08-07 23:28:28', '2021-08-07 23:44:55', '请求127.0.0.1')",
                "INSERT INTO \"{$tableName}\" VALUES (2, '每月1号清理所有日志', '2', 'App\\System\\Crontab\\ClearLogCrontab', '', '0 0 0 1 * *', 2, 2, NULL, NULL, '2022-04-11 11:15:48', '2022-04-11 11:15:48', '')",
            ];
        }
        return [
            "INSERT INTO `{$tableName}` VALUES (1, 'urlCrontab', '3', 'http://127.0.0.1:9501/', '', '59 */1 * * * *', 2, 2, NULL, NULL, '2021-08-07 23:28:28', '2021-08-07 23:44:55', '请求127.0.0.1')",
            "INSERT INTO `{$tableName}` VALUES (2, '每月1号清理所有日志', '2', 'App\\System\\Crontab\\ClearLogCrontab', '', '0 0 0 1 * *', 2, 2, NULL, NULL, '2022-04-11 11:15:48', '2022-04-11 11:15:48', '')",
        ];
    }
}
