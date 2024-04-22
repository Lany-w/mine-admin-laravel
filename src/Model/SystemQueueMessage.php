<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 16:52
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Traits\PageList;

/**
 * @property int $id 主键
 * @property int $content_id 内容ID
 * @property string $content_type 内容类型
 * @property string $title 消息标题
 * @property int $send_by 发送人
 * @property string $content 消息内容
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $remark 备注
 * @property Collection|SystemUser[] $receiveUser
 * @property SystemUser $sendUser
 */
class SystemQueueMessage extends MineModel
{
    protected $table = 'system_queue_message';


    public function handleSearch(Builder $query, array $params): Builder
    {
        $userId = Mine::guard()->user()->id;
        if (isset($params['title']) && filled($params['title'])) {
            $query->where('title', 'like', '%' . $params['title'] . '%');
        }

        // 内容类型
        if (isset($params['content_type']) && filled($params['content_type']) && $params['content_type'] !== 'all') {
            $query->where('content_type', '=', $params['content_type']);
        }

        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }

        // 获取收信数据
        if (isset($params['getReceive']) && filled($params['getReceive'])) {
            $query->with(['sendUser' => function ($query) {
                $query->select(['id', 'username', 'nickname', 'avatar']);
            }]);
            $prefix = config('database.connections.'.env('DB_CONNECTION').'.prefix');
            $readStatus = $params['read_status'] ?? 'all';

            if (env('DB_DRIVER') == 'pgsql') {
                $sql = <<<sql
                    id IN (
                        SELECT "message_id" FROM "{$prefix}system_queue_message_receive" WHERE "user_id" = ?
                        AND (CASE WHEN CAST(? AS varchar) <> 'all' THEN CAST("read_status" as varchar) = ? ELSE  1 = 1  END)
                    )
                sql;
            } else {
                $sql = <<<sql
                    id IN (
                        SELECT `message_id` FROM `{$prefix}system_queue_message_receive` WHERE `user_id` = ?
                        AND if (? <> 'all', `read_status` = ?, ' 1 = 1 ')
                    )
                sql;
            }
            $query->whereRaw($sql, [$params['user_id'] ?? $userId, $readStatus, $readStatus]);
        }

        // 收取发信数据
        if (isset($params['getSend']) && filled($params['getSend'])) {
            $query->where('send_by', $userId);
        }

        return $query;
    }
}