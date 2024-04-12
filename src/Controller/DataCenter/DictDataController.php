<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 14:56
 */

namespace Lany\MineAdmin\Controller\DataCenter;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Model\SystemDictData;

class DictDataController extends MineController
{
    /**
     * Notes:快捷查询一个字典.
     * User: Lany
     * DateTime: 2024/4/11 14:57
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        return $this->success(app('SystemDictDataService')->getList($request->all()));
    }
}