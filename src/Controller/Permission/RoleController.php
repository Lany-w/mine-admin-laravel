<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 13:52
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Lany\MineAdmin\Helper\Annotation\OperationLog;
use Lany\MineAdmin\Helper\Annotation\Permission;
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
    #[Permission('system:role, system:role:index')]
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
    #[Permission('system:role:save'), OperationLog]
    public function save(SystemRoleRequest $request): JsonResponse
    {
        return $this->success(['id' => $this->service->save($request->input())]);
    }

    /**
     * 单个或批量删除数据到回收站.
     */
    #[Permission('system:role:delete')]
    public function delete(): JsonResponse
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }
}