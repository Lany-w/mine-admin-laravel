<?php
namespace Lany\MineAdmin\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SystemDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run()
    {
        Db::table('system_dept')->truncate();
        Db::table('system_dept')->insert(
            [
                'parent_id' => 0,
                'level' => '0',
                'name' => '睿能科技',
                'leader' => 'Todd',
                'phone' => '16888888888',
                'created_by' => env('SUPER_ADMIN', 1),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
