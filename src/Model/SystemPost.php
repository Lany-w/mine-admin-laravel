<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 14:20
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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