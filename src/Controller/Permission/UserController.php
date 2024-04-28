<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 16:50
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Model\SystemUser;
use Lany\MineAdmin\Requests\ChangePasswordRequest;
use Lany\MineAdmin\Services\SystemUserService;

class UserController extends MineController
{
    protected SystemUserService $service;
    /**
     * Notes:用户列表
     * User: Lany
     * DateTime: 2024/4/12 16:51
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success($this->service->getPageList($request->all(), false));
    }

    /**
     * 更改用户资料，含修改头像
     */
    public function updateInfo(): JsonResponse
    {
        return $this->service->updateInfo(array_merge($this->request->all(), ['id' => user()->getId()])) ? $this->success(t('mineadmin.response_success')) : $this->error();
    }

    /**
     * 修改密码 (不验证权限).
     */
    public function modifyPassword(ChangePasswordRequest $request): JsonResponse
    {
        return $this->service->modifyPassword($request->input()) ? $this->success() : $this->error();
    }
}