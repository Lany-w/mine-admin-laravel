<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 10:46
 */
namespace Lany\MineAdmin\Services;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Exceptions\UserBanException;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Model\SystemMenu;
use Lany\MineAdmin\Model\SystemRole;
use Lany\MineAdmin\Model\SystemUser;
use Illuminate\Support\Facades\Hash;
class SystemUserService extends SystemService
{
    public string $model = SystemUser::class;
    public function login(): null|string
    {
        $model = new SystemUser();
        $user = $model->checkUserByUsername(request()->input('username'));
        if (Hash::check(request()->input('password'), $user->password)) {
            if (
                ($user->status == SystemUser::USER_NORMAL)
                || ($user->status == SystemUser::USER_BAN && $user->id == config('mine_admin.super_admin_id'))
            ) {
                $token = Mine::guard()->login($user);
                UserLoginAfter::dispatch($user, true);
                return $token;
            }

            UserLoginAfter::dispatch($user, -1);
            throw new UserBanException();
        }
        UserLoginAfter::dispatch($user, false);
        throw new NormalStatusException();
    }
    public function getInfo(): array
    {
        $user = Mine::guard()->user();
        if (!$user) {
            throw new MineException(trans('system.unable_get_userinfo'), 500);
        }
        if ($user->isSuperAdmin()) {
            $data['roles'] = ['superAdmin'];
            $data['routers'] = app(SystemMenu::class)->getSuperAdminRouters();
            $data['codes'] = ['*'];
        } else {
            $roles = app(SystemRole::class)->getMenuIdsByRoleIds($user->roles()->pluck('id')->toArray());
            $ids = $this->filterMenuIds($roles);
            $data['roles'] = $user->roles()->pluck('code')->toArray();
            $data['routers'] = app(SystemMenu::class)->getRoutersByIds($ids);
            $data['codes'] = app(SystemMenu::class)->getMenuCode($ids);
        }
        $data['user'] = $user->toArray();

        return $data;
    }

    /**
     * 过滤通过角色查询出来的菜单id列表，并去重.
     */
    protected function filterMenuIds(array &$roleData): array
    {
        $ids = [];
        foreach ($roleData as $val) {
            foreach ($val['menus'] as $menu) {
                $ids[] = $menu['id'];
            }
        }
        unset($roleData);
        return array_unique($ids);
    }
}