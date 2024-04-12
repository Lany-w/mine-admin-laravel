<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 16:51
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Model\SystemQueueMessage;

class QueueMessageController extends MineController
{
    /**
     * Notes:接收消息列表
     * User: Lany
     * DateTime: 2024/4/11 16:52
     * @param Request $request
     * @return JsonResponse
     */
    public function receiveList(Request $request): JsonResponse
    {
        $params = $request->all();
        $params['getReceive'] = true;
        unset($params['getSend']);
        return $this->success(app(SystemQueueMessage::class)->getPageList($params, false));
    }
}