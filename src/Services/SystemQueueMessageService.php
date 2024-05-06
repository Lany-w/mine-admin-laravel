<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/28 12:53
 */

namespace Lany\MineAdmin\Services;

use Lany\MineAdmin\Model\SystemQueueMessage;

class SystemQueueMessageService extends SystemService
{
    public string $model = SystemQueueMessage::class;

    /**
     * 获取收信箱列表数据.
     */
    public function getReceiveMessage(array $params = []): array
    {
        $params['getReceive'] = true;
        unset($params['getSend']);
        return app($this->model)->getPageList($params, false);
    }

    /**
     * 获取已发送列表数据.
     */
    public function getSendMessage(array $params = []): array
    {
        $params['getSend'] = true;
        unset($params['getReceive']);
        return app($this->model)->getPageList($params, false);
    }

    /**
     * 发私信
     */
    public function sendPrivateMessage(array $data): bool
    {
        $queueMessage = new QueueMessageVo();
        $queueMessage->setTitle($data['title']);
        $queueMessage->setContent($data['content']);
        // 固定私信类型
        $queueMessage->setContentType(SystemQueueMessage::TYPE_PRIVATE_MESSAGE);
        $queueMessage->setSendBy(user()->getId());
        return push_queue_message($queueMessage, $data['users']) !== -1;
    }
}