<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 12:46
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id 主键
 * @property int $message_id 消息ID
 * @property string $title 标题
 * @property int $type 公告类型（1通知 2公告）
 * @property string $content 公告内容
 * @property int $click_num 浏览次数
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemNotice extends MineModel
{
    use SoftDeletes;
    protected $table = 'system_notice';


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['title']) && filled($params['title'])) {
            $query->where('title', '=', $params['title']);
        }

        if (isset($params['type']) && filled($params['type'])) {
            $query->where('type', '=', $params['type']);
        }

        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }

        return $query;
    }

}