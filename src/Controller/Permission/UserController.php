<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 16:50
 */

namespace Lany\MineAdmin\Controller\Permission;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lany\MineAdmin\Admin\System\Dto\UserDto;
use Lany\MineAdmin\Controller\MineController;
use Lany\MineAdmin\Helper\Annotation\ExcelProperty;
use Lany\MineAdmin\Helper\Annotation\Handle\ExcelPropertyAnnotation;
use Lany\MineAdmin\Helper\Annotation\Permission;
use Lany\MineAdmin\Helper\MineCollection;
use Lany\MineAdmin\Helper\Annotation\OperationLog;
use Lany\MineAdmin\Model\SystemUser;
use Lany\MineAdmin\Requests\ChangePasswordRequest;
use Lany\MineAdmin\Requests\ChangeStatusRequest;
use Lany\MineAdmin\Requests\SystemUserRequest;
use Lany\MineAdmin\Requests\SystemUserSaveRequest;
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
    #[Permission('system:user, system:user:index')]
    public function index(Request $request): JsonResponse
    {
        return $this->success($this->service->getPageList($request->all(), false));
    }

    /**
     * 获取一个用户信息.
     */
    #[Permission('system:user:read')]
    public function read(int $id): JsonResponse
    {
        return $this->success($this->service->read($id));
    }

    /**
     * 更新一个用户信息.
     */
    #[Permission('system:user:update'), OperationLog]
    public function update(int $id, SystemUserRequest $request): JsonResponse
    {
        return $this->service->update($id, $request->all()) ? $this->success() : $this->error();
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

    /**
     * 新增一个用户.
     */
    #[Permission('system:user:save'), OperationLog]
    public function save(SystemUserSaveRequest $request): JsonResponse
    {
        return $this->success(['id' => $this->service->save($request->all())]);
    }

    /**
     * 单个或批量删除用户到回收站.
     */
    #[Permission('system:user:delete')]
    public function delete(): JsonResponse
    {
        return $this->service->delete((array) $this->request->input('ids', [])) ? $this->success() : $this->error();
    }

    /**
     * 更改用户状态
     */
    #[Permission('system:user:changeStatus')]
    public function changeStatus(ChangeStatusRequest $request): JsonResponse
    {
        return $this->service->changeStatus((int) $request->input('id'), (string) $request->input('status'))
            ? $this->success() : $this->error();
    }

    /**
     * 清除用户缓存.
     */
    #[Permission('system:user:cache')]
    public function clearCache(): JsonResponse
    {
        $this->service->clearCache((string) $this->request->input('id', null));
        return $this->success();
    }

    /**
     * 设置用户首页.
     */
    #[Permission('system:user:homePage')]
    public function setHomePage(SystemUserRequest $request): JsonResponse
    {
        return $this->service->setHomePage($request->validated()) ? $this->success() : $this->error();
    }

    /**
     * 初始化用户密码
     */
    #[Permission('system:user:initUserPassword')]
    public function initUserPassword(): JsonResponse
    {
        //OperationLog::$FLAG = true;
        return $this->service->initUserPassword((int) $this->request->input('id')) ? $this->success() : $this->error();
    }

    /**
     * 下载导入模板
     * @throws \ReflectionException
     */
    #[OperationLog]
    public function downloadTemplate(): ?\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return (new MineCollection())->export(UserDto::class,'模板下载', []);
    }

    /**
     * 用户导入.
     */
    #[Permission('system:user:import')]
    public function import(): JsonResponse
    {
        return $this->service->import(UserDto::class) ? $this->success() : $this->error();
    }

    /**
     * 用户导出.
     */
    #[Permission('system:user:export'), OperationLog]
    public function export(): ?\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return $this->service->export($this->request->all(), UserDto::class, '用户列表');
    }

}