<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 10:53
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Helper\Annotation\OperationLog;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Requests\SystemDeptRequest;
use Lany\MineAdmin\Services\SystemDeptService;

class DeptController extends MineController
{
    protected SystemDeptService $service;
    /**
     * Notes:部门树列表.
     * User: Lany
     * DateTime: 2024/4/19 13:48
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(app('SystemDeptService')->getTreeList($request->all()));
    }

    #[Permission('system:dept, system:dept:index')]
    public function getLeaderList(): JsonResponse
    {
        return $this->success($this->service->getLeaderList($this->request->all()));
    }

    /**
     * 新增部门领导
     */
    #[Permission('system:dept:update'), OperationLog('新增部门领导')]
    public function addLeader(SystemDeptRequest $request): JsonResponse
    {
        return $this->service->addLeader($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 删除部门领导
     */
    #[Permission('system:dept:delete'), OperationLog('删除部门领导')]
    public function delLeader(SystemDeptRequest $request): JsonResponse
    {
        return $this->service->delLeader($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 更新部门.
     */
    #[Permission('system:dept:update'), OperationLog]
    public function update(int $id, SystemDeptRequest $request): JsonResponse
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
    }

    /**
     * 单个或批量删除部门到回收站.
     */
    #[Permission('system:dept:delete')]
    public function delete(): JsonResponse
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 更改部门状态
     */
    #[Permission('system:dept:changeStatus'), OperationLog]
    public function changeStatus(SystemDeptRequest $request): JsonResponse
    {
        return $this->service->changeStatus((int) $request->input('id'), (string) $request->input('status'))
            ? $this->success() : $this->error();
    }

    /**
     * 数字运算操作.
     */
    #[Permission('system:dept:update'), OperationLog]
    public function numberOperation(): JsonResponse
    {
        return $this->service->numberOperation(
            (int) $this->request->input('id'),
            (string) $this->request->input('numberName'),
            (int) $this->request->input('numberValue', 1),
        ) ? $this->success() : $this->error();
    }

    /**
     * Notes:前端选择树（不需要权限）.
     * User: Lany
     * DateTime: 2024/4/12 10:54
     * @param Request $request
     * @return JsonResponse
     */
    public function tree(Request $request): JsonResponse
    {
        return $this->success(app('SystemDeptService')->getSelectTree());
    }

    /**
     * 新增部门.
     */
    #[Permission('system:dept:save'), OperationLog]
    public function save(SystemDeptRequest $request): JsonResponse
    {
        return $this->success(['id' => $this->service->save($request->all())]);
    }
}