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
     * 更新角色.
     */
    #[Permission('system:role:update'), OperationLog]
    public function update(int $id, SystemRoleRequest $request): JsonResponse
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除数据到回收站.
     */
    #[Permission('system:role:delete')]
    public function delete(): JsonResponse
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 通过角色获取菜单.
     */
    public function getMenuByRole(int $id): JsonResponse
    {
        return $this->success($this->service->getMenuByRole($id));
    }

    /**
     * 通过角色获取部门.
     */
    public function getDeptByRole(int $id): JsonResponse
    {
        return $this->success($this->service->getDeptByRole($id));
    }

    /**
     * 更新用户数据权限.
     */
    #[Permission('system:role:dataPermission'), OperationLog]
    public function dataPermission(int $id): JsonResponse
    {
        return $this->service->update($id, $this->request->all()) ? $this->success() : $this->error();
    }

    /**
     * 更新用户菜单权限.
     */
    #[Permission('system:role:menuPermission'), OperationLog]
    public function menuPermission(int $id): JsonResponse
    {
        return $this->service->update($id, $this->request->all()) ? $this->success() : $this->error();
    }

    /**
     * 更改角色状态
     */
    #[Permission('system:role:changeStatus'), OperationLog]
    public function changeStatus(SystemRoleRequest $request): JsonResponse
    {
        return $this->service->changeStatus((int) $request->input('id'), (string) $request->input('status'))
            ? $this->success() : $this->error();
    }

    /**
     * 数字运算操作.
     */
    #[Permission('system:role:update'), OperationLog]
    public function numberOperation(): JsonResponse
    {
        return $this->service->numberOperation(
            (int) $this->request->input('id'),
            (string) $this->request->input('numberName'),
            (int) $this->request->input('numberValue', 1),
        ) ? $this->success() : $this->error();
    }
}