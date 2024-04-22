<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 09:45
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;

class NoticeController extends MineController
{
    /**
     * 列表.
     */
    public function index(): JsonResponse
    {
        return $this->success(app('SystemNoticeService')->getPageList($this->request->all()));
    }
}