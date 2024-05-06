<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 16:51
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Services\SystemQueueMessageService;

class QueueMessageController extends MineController
{
    protected SystemQueueMessageService $service;

    /**
     * Notes:接收消息列表
     * User: Lany
     * DateTime: 2024/4/11 16:52
     * @return JsonResponse
     */
    public function receiveList(): JsonResponse
    {
        return $this->success($this->service->getReceiveMessage($this->request->all()));
    }

    /**
     * 已发送消息列表.
     */
    public function sendList(): JsonResponse
    {
        return $this->success($this->service->getSendMessage($this->request->all()));
    }

    /**
     * 发私信
     */
    public function sendPrivateMessage(MessageRequest $request): JsonResponse
    {
        return $this->service->sendPrivateMessage($request->input()) ? $this->success() : $this->error();
    }
}