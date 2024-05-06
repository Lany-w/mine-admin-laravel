<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:52
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Helper\Permission;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Requests\SystemRoleRequest;
use Lany\MineAdmin\Services\SystemRoleService;

class RoleController extends MineController
{
    protected SystemRoleService $service;

    /**
     * Notes:角色分页列表
     * User: Lany
     * DateTime: 2024/4/19 13:34
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->success($this->service->getPageList($this->request->all()));
    }

    /**
     * Notes:获取角色列表 (不验证权限).
     * User: Lany
     * DateTime: 2024/4/12 13:53
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->success($this->service->getList());
    }

    /**
     * 新增角色.
     */
    #[Permission('system:role:save')]
    public function save(SystemRoleRequest $request): JsonResponse
    {
        return $this->success(['id' => $this->service->save($request->input())]);
    }
}