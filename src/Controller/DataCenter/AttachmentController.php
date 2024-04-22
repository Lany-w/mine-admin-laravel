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
use ServiceBuilder;

class AttachmentController extends MineController
{
    #[ServiceBuilder('SystemUploadFileService')]
    protected $service;
    /**
     * 列表数据.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success($this->service->getPageList($request->all()));
    }
}