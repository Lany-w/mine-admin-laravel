<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 17:24
 */

namespace Lany\MineAdmin\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Model\SystemLoginLog;
use Lany\MineAdmin\Model\SystemNotice;
use Lany\MineAdmin\Model\SystemOperLog;

class CommonController extends MineController
{
    /**
     * Notes:获取公告列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @param Request $request
     * @return JsonResponse
     */
    public function getNoticeList(Request $request):JsonResponse
    {
        return $this->success(app(SystemNotice::class)->getPageList($request->all()));
    }

    /**
     * Notes:获取登录日志列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @param Request $request
     * @return JsonResponse
     */
    public function getLoginLogPageList(Request $request): JsonResponse
    {
        return $this->success(app(SystemLoginLog::class)->getPageList($request->all()));
    }

    /**
     * Notes:获取操作日志列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @param Request $request
     * @return JsonResponse
     */
    public function getOperLogPageList(Request $request): JsonResponse
    {
        return $this->success(app(SystemOperLog::class)->getPageList($request->all()));
    }
}