<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 10:36
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $storage_mode 存储模式 (1 本地 2 阿里云 3 七牛云 4 腾讯云)
 * @property string $origin_name 原文件名
 * @property string $object_name 新文件名
 * @property string $hash 文件hash
 * @property string $mime_type 资源类型
 * @property string $storage_path 存储目录
 * @property string $suffix 文件后缀
 * @property int $size_byte 字节数
 * @property string $size_info 文件大小
 * @property string $url url地址
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemUploadFile extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_uploadfile';

    /**
     * 通过hash获取上传文件的信息.
     * @param string $hash
     * @param array $columns
     * @return Model|Builder|null
     */
    public function getFileInfoByHash(string $hash, array $columns = ['*']): Model|Builder|null
    {
        $model = self::query()->where('hash', $hash)->first($columns);
        if (!$model) {
            $model = self::withTrashed()->where('hash', $hash)->first(['id']);
            $model && $model->forceDelete();
            return null;
        }
        return $model;
    }

    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['storage_mode']) && filled($params['storage_mode'])) {
            $query->where('storage_mode', $params['storage_mode']);
        }
        if (isset($params['origin_name']) && filled($params['origin_name'])) {
            $query->where('origin_name', 'like', '%' . $params['origin_name'] . '%');
        }
        if (isset($params['storage_path']) && filled($params['storage_path'])) {
            $query->where('storage_path', 'like', $params['storage_path'] . '%');
        }
        if (isset($params['mime_type']) && filled($params['mime_type'])) {
            $query->where('mime_type', 'like', $params['mime_type'] . '/%');
        }
        if (isset($params['minDate']) && filled($params['minDate']) && isset($params['maxDate']) && filled($params['maxDate'])) {
            $query->whereBetween(
                'created_at',
                [$params['minDate'] . ' 00:00:00', $params['maxDate'] . ' 23:59:59']
            );
        }
        return $query;
    }
}