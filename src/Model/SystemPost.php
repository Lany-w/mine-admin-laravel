<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 14:20
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id 主键
 * @property string $name 岗位名称
 * @property string $code 岗位代码
 * @property int $sort 排序
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemUser[] $users
 */
class SystemPost extends MineModel
{
    use SoftDeletes;

    protected $table = 'system_post';

    /**
     * 通过中间表获取用户.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SystemUser::class, 'system_user_post', 'post_id', 'user_id');
    }
}