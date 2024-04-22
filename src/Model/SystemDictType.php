<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:33
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Lany\MineAdmin\Traits\PageList;

/**
 * @property int $id 主键
 * @property string $name 字典名称
 * @property string $code 字典标示
 * @property int $status 状态 (1正常 2停用)
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property Collection|SystemDictData[] $dictData
 */
class SystemDictType extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_dict_type';

    /**
     * 关联字典数据表.
     */
    public function dictData(): HasMany
    {
        return $this->hasMany(SystemDictData::class, 'type_id', 'id');
    }
}