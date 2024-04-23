<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 13:45
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Lany\MineAdmin\Exceptions\MineException;

/**
 * @property int $id 主键
 * @property string $source_name 数据源名称
 * @property string $dsn 连接dsn字符串
 * @property string $username 数据库用户
 * @property string $password 数据库密码
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $remark 备注
 */
class SettingDatasource extends MineModel
{
    protected $table = 'setting_datasource';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        // 数据源名称
        if (isset($params['source_name']) && filled($params['source_name'])) {
            $query->where('source_name', 'like', '%' . $params['source_name'] . '%');
        }

        return $query;
    }

    /**
     * 测试数据库连接.
     */
    public function getDataSourceTableList(array|object $params): array
    {
        try {
            return $this->connectionDb($params)->query('SHOW TABLE STATUS')->fetchAll();
        } catch (\Throwable $e) {
            throw new MineException($e->getMessage(), 500);
        }
    }

    /**
     * 获取创建表结构SQL.
     */
    public function getCreateTableSql(array|object $params, string $tableName): string
    {
        try {
            return $this->connectionDb($params)->query(
                sprintf('SHOW CREATE TABLE %s', $tableName)
            )->fetch()['Create Table'];
        } catch (\Throwable $e) {
            throw new MineException($e->getMessage(), 500);
        }
    }

    /**
     * 通过SQL创建表.
     */
    public function createTable(string $sql): bool
    {
        return Db::connection('default')->getPdo()->exec($sql) > 0;
    }

    public function connectionDb(array|object $params): \PDO
    {
        return new \PDO($params['dsn'], $params['username'], $params['password'], [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);
    }

}