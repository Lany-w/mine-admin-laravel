<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 17:24
 */

namespace Lany\MineAdmin\Controller;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Model\SystemNotice;

class CommonController extends MineController
{
    /**
     * Notes:获取公告列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @return JsonResponse
     */
    public function getNoticeList():JsonResponse
    {
        return $this->success(app(SystemNotice::class)->getPageList());
    }


}