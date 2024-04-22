<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 10:09
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id 主键
 * @property string $name 应用组名称
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemAppGroup extends MineModel
{
    use SoftDeletes;

    protected $table = 'system_app_group';
}