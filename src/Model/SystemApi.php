<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 11:04
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property int $id 主键
 * @property int $group_id 接口组ID
 * @property string $name 接口名称
 * @property string $access_name 接口访问名称
 * @property string $class_name 类命名空间
 * @property string $method_name 方法名
 * @property int $auth_mode 认证模式 (1简易 2复杂)
 * @property string $request_mode 请求模式 (A 所有 P POST G GET)
 * @property string $description 接口说明介绍
 * @property string $response 返回内容示例
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemApiColumn[] $apiColumn
 * @property SystemApiGroup $apiGroup
 * @property Collection|SystemApp[] $apps
 */
class SystemApi extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_api';

    /**
     * 通过中间表关联APP.
     */
    public function apps(): BelongsToMany
    {
        return $this->belongsToMany(SystemApp::class, 'system_app_api', 'api_id', 'app_id');
    }

    /**
     * 关联API分组.
     */
    public function apiGroup(): HasOne
    {
        return $this->hasOne(SystemApiGroup::class, 'id', 'group_id');
    }

    /**
     * 关联API字段.
     */
    public function apiColumn(): HasMany
    {
        return $this->hasMany(SystemApiColumn::class, 'api_id', 'id');
    }
}