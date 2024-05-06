<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 10:46
 */
namespace Lany\MineAdmin\Services;
use Illuminate\Support\Facades\Redis;
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
                UserLoginAfter::dispatch($user, true, $token);
                return $token;
            }

            UserLoginAfter::dispatch($user, -1);
            throw new UserBanException();
        }
        UserLoginAfter::dispatch($user, false, '');
        throw new NormalStatusException();
    }
    public function getInfo(): array
    {
        $user = Mine::guard()->user();
        if (!$user) {
            throw new MineException(trans('system.unable_get_userinfo'), 500);
        }
        return cache()->store('redis')->rememberForever('loginInfo_userId_'.$user->id,function () use ($user) {
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
        });
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

    /**
     * 获取在线用户.
     */
    public function getOnlineUserPageList(array $params = []): array
    {

        $key = config('database.redis.options.prefix').'Token:*';
        //$jwt = $this->container->get(JWT::class);
        //$blackList = $this->container->get(JWT::class)->blackList;
        $userIds = [];
        $iterator = null;
        $params = [$iterator, $key, 100];
        // 执行 SCAN 命令
        do {
            $users = Redis::command('SCAN', $params);
            foreach ($users as $user) {
                // 如果是已经加入到黑名单的就代表不是登录状态了
                /*if (! $this->hasTokenBlack($redis->get($user)) && preg_match("/{$key}(\\d+)$/", $user, $match) && isset($match[1])) {
                    $userIds[] = $match[1];
                }*/
                preg_match("/{$key}(\\d+)$/", $user, $match);
                $userIds[] = $match[1];
            }
        } while ($iterator != 0);

        if (empty($userIds)) {
            return [];
        }

        return $this->getPageList(array_merge(['userIds' => $userIds], $params));
    }

    /**
     * 用户更新个人资料.
     */
    public function updateInfo(array $params): bool
    {
        if (! isset($params['id'])) {
            return false;
        }

        $model = app($this->model)::find($params['id']);
        unset($params['id'], $params['password']);
        foreach ($params as $key => $param) {
            $model[$key] = $param;
        }

        $this->clearCache((string) $model['id']);
        return $model->save();
    }

    /**
     * 用户修改密码
     */
    public function modifyPassword(array $params): bool
    {
        return app($this->model)->initUserPassword(user()->getId(), $params['newPassword']);
    }

    /**
     * 清除用户缓存.
     */
    public function clearCache(string $id): bool
    {
        $redis = redis();
        $prefix = config('cache.prefix');

        $iterator = null;
        do {
            $configKey = Redis::command('SCAN', [$iterator, $prefix . 'config:*', 100]);
            $redis->del($configKey);
        } while ($iterator != 0);

        do {
            $dictKey = Redis::command('SCAN', [$iterator, $prefix . 'system:dict:*', 100]);
            $redis->del($dictKey);
        } while ($iterator != 0);

        $redis->del($prefix . 'crontab', $prefix . 'modules');

        return $redis->del("{$prefix}loginInfo:userId_{$id}") > 0;
    }

    /**
     * 根据用户ID列表获取用户基础信息.
     */
    public function getUserInfoByIds(array $ids, ?array $select = null): array
    {
        if (! $select) {
            $select = ['id', 'username', 'nickname', 'phone', 'email', 'created_at'];
        }
        return SystemUser::query()->whereIn('id', $ids)->select($select)->get()->toArray();
    }
}