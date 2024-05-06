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
use Lany\MineAdmin\Services\SystemDeptService;
use Lany\MineAdmin\Services\SystemLoginLogService;
use Lany\MineAdmin\Services\SystemNoticeService;
use Lany\MineAdmin\Services\SystemOperLogService;
use Lany\MineAdmin\Services\SystemPostService;
use Lany\MineAdmin\Services\SystemRoleService;
use Lany\MineAdmin\Services\SystemUserService;

class CommonController extends MineController
{
    protected SystemNoticeService $noticeService;
    protected SystemLoginLogService $loginLogService;
    protected SystemOperLogService $operLogService;
    protected SystemDeptService $deptService;
    protected SystemRoleService $roleService;
    protected SystemPostService $postService;
    protected SystemUserService $userService;

    /**
     * Notes:获取公告列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @return JsonResponse
     */
    public function getNoticeList():JsonResponse
    {
        return $this->success($this->noticeService->getPageList($this->request->all()));
    }

    /**
     * Notes:获取登录日志列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @return JsonResponse
     */
    public function getLoginLogPageList(): JsonResponse
    {
        return $this->success($this->loginLogService->getPageList($this->request->all()));
    }

    /**
     * Notes:获取操作日志列表
     * User: Lany
     * DateTime: 2024/4/11 13:12
     * @return JsonResponse
     */
    public function getOperLogPageList(): JsonResponse
    {
        return $this->success($this->operLogService->getPageList($this->request->all()));
    }

    public function getResourceList(Request $request): JsonResponse
    {
        return $this->success(app(SystemOperLog::class)->getPageList($request->all()));
    }

    /**
     * 获取部门树列表.
     */
    public function getDeptTreeList(): JsonResponse
    {
        return $this->success($this->deptService->getSelectTree());
    }

    /**
     * 获取角色列表.
     */
    public function getRoleList(): JsonResponse
    {
        return $this->success($this->roleService->getList());
    }

    /**
     * 获取岗位列表.
     */
    public function getPostList(): JsonResponse
    {
        return $this->success($this->postService->getList());
    }

    /**
     * 获取用户列表.
     */
    public function getUserList(): JsonResponse
    {
        return $this->success($this->userService->getPageList($this->request->all()));
    }

    /**
     * 通过 id 列表获取用户基础信息.
     */
    public function getUserInfoByIds(): JsonResponse
    {
        return $this->success($this->userService->getUserInfoByIds((array) $this->request->input('ids', [])));
    }
}