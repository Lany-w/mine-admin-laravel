<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 10:03
 */

namespace Lany\MineAdmin\Controller\App;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Controller\MineController;

class SystemAppGroupController extends MineController
{
    /**
     * 列表.
     */
    public function index(): JsonResponse
    {
        return $this->success(app('SystemAppGroupService')->getPageList($this->request->all()));
    }
}