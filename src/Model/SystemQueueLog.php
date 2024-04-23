<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 11:01
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id 主键
 * @property string $exchange_name 交换机名称
 * @property string $routing_key_name 路由名称
 * @property string $queue_name 队列名称
 * @property string $queue_content 队列数据
 * @property string $log_content 队列日志
 * @property int $produce_status 生产状态 1:未生产 2:生产中 3:生产成功 4:生产失败 5:生产重复
 * @property int $consume_status 消费状态 1:未消费 2:消费中 3:消费成功 4:消费失败 5:消费重复
 * @property int $delay_time 延迟时间（秒）
 * @property int $created_by 创建者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 */
class SystemQueueLog extends MineModel
{
    /**
     * @Message("未生产")
     */
    public const PRODUCE_STATUS_WAITING = 1;

    /**
     * @Message("生产中")
     */
    public const PRODUCE_STATUS_DOING = 2;

    /**
     * @Message("生产成功")
     */
    public const PRODUCE_STATUS_SUCCESS = 3;

    /**
     * @Message("生产失败")
     */
    public const PRODUCE_STATUS_FAIL = 4;

    /**
     * @Message("生产重复")
     */
    public const PRODUCE_STATUS_REPEAT = 5;

    /**
     * @Message("未消费")
     */
    public const CONSUME_STATUS_NO = 1;

    /**
     * @Message("消费中")
     */
    public const CONSUME_STATUS_DOING = 2;

    /**
     * @Message("消费成功")
     */
    public const CONSUME_STATUS_SUCCESS = 3;

    /**
     * @Message("消费失败")
     */
    public const CONSUME_STATUS_FAIL = 4;

    /**
     * @Message("消费重复")
     */
    public const CONSUME_STATUS_REPEAT = 5;

    /**
     * The table associated with the model.
     */
    protected $table = 'system_queue_log';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        // 交换机名称
        if (isset($params['exchange_name']) && filled($params['exchange_name'])) {
            $query->where('exchange_name', '=', $params['exchange_name']);
        }

        // 路由名称
        if (isset($params['routing_key_name']) && filled($params['routing_key_name'])) {
            $query->where('routing_key_name', '=', $params['routing_key_name']);
        }

        // 队列名称
        if (isset($params['queue_name']) && filled($params['queue_name'])) {
            $query->where('queue_name', '=', $params['queue_name']);
        }

        // 生产状态 0:未生产 1:生产中 2:生产成功 3:生产失败 4:生产重复
        if (isset($params['produce_status']) && filled($params['produce_status'])) {
            $query->where('produce_status', '=', $params['produce_status']);
        }

        // 消费状态 0:未消费 1:消费中 2:消费成功 3:消费失败 4:消费重复
        if (isset($params['consume_status']) && filled($params['consume_status'])) {
            $query->where('consume_status', '=', $params['consume_status']);
        }

        return $query;
    }
}