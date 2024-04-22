<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:50
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;

class AttachmentController extends MineController
{
    /**
     * 列表数据.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemUploadFileService')->getPageList($request->all()));
    }
}