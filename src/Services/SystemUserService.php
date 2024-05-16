<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/10 10:46
 */
namespace Lany\MineAdmin\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Lany\MineAdmin\Events\ClearCache;
use Lany\MineAdmin\Events\UserAdd;
use Lany\MineAdmin\Events\UserDelete;
use Lany\MineAdmin\Events\UserLoginAfter;
use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Exceptions\UserBanException;
use Lany\MineAdmin\Helper\MineCollection;
use Lany\MineAdmin\Middlewares\OperationLog;
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
            $users = Redis::connection('cache')->command('SCAN', $params);
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
        $redis = Redis::connection('cache');
        $prefix = config('cache.prefix');

        $iterator = null;
        do {
            $configKey = $redis->command('SCAN', [$iterator, 'config:*', 100]);
            $redis->command('del', [$configKey]);
        } while ($iterator != 0);

        do {
            $dictKey = $redis->command('SCAN', [$iterator, 'system_dict_*', 100]);
            $redis->command('del', [$dictKey]);
        } while ($iterator != 0);

        $redis->command('del', ['crontab', 'modules']);

        return $redis->command('del', ["{$prefix}:loginInfo_userId_{$id}"]) > 0;
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

    /**
     * 新增用户.
     */
    public function save(array $data): mixed
    {
        if (app($this->model)->existsByUsername($data['username'])) {
            throw new NormalStatusException(t('system.username_exists'));
        }
        $data['password'] = Hash::make($data['password']);
        $data = $this->handleData($data);

        $role_ids = $data['role_ids'] ?? [];
        $post_ids = $data['post_ids'] ?? [];
        $dept_ids = $data['dept_ids'] ?? [];
        $this->filterExecuteAttributes($data, true);

        DB::beginTransaction();
        try {
            $user = app($this->model)::query()->create($data);
            $user->roles()->sync($role_ids, false);
            $user->posts()->sync($post_ids, false);
            $user->depts()->sync($dept_ids, false);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new MineException($e->getMessage());
        }

        $data['id'] = $user->id;
        UserAdd::dispatch($data);
        OperationLog::$FLAG = true;

        return $user->id;
    }

    /**
     * 删除用户.
     */
    public function delete(array $ids): bool
    {
        if (! empty($ids)) {
            if (($key = array_search(config('mine_admin.super_admin_id'), $ids)) !== false) {
                unset($ids[$key]);
            }
            $result = app($this->model)::destroy($ids);
            event(new UserDelete($ids));
            return $result;
        }

        return false;
    }

    public function import(string $dto, ?\Closure $closure = null): bool
    {
        return Db::transaction(function () use ($dto, $closure) {
            return (new MineCollection())->import($dto, new $this->model, $closure);
        });
    }

    public function export(array $params, ?string $dto, ?string $filename = null, ?\Closure $callbackData = null)
    {
        OperationLog::$FLAG = true;
        if (empty($dto)) {
            abort(500, '导出未指定DTO');
        }

        if (empty($filename)) {
            $filename = app($this->model)->getTable();
        }

        return (new MineCollection())->export($dto, $filename, app($this->model)->getList($params), $callbackData);
    }

    public function update(mixed $id, array $data): bool
    {
        if (array_key_exists('username', $data)) {
            unset($data['username']);
        }
        if (array_key_exists('password', $data)) {
            unset($data['password']);
        }
        ClearCache::dispatch("loginInfo_userId_".$id);

        $model = app($this->model)->find($id);
        return $model->update($this->handleData($data));
    }

    /**
     * 设置用户首页.
     */
    public function setHomePage(array $params): bool
    {
        $res = app($this->model)::query()
                ->where('id', $params['id'])
                ->update(['dashboard' => $params['dashboard']]) > 0;

        $this->clearCache((string) $params['id']);
        return $res;
    }

    /**
     * 初始化用户密码
     */
    public function initUserPassword(int $id, string $password = '123456'): bool
    {
        return app($this->model)->initUserPassword($id, $password);
    }


    /**
     * 处理提交数据.
     * @param mixed $data
     */
    protected function handleData(array $data): array
    {
        if (! is_array($data['role_ids'])) {
            $data['role_ids'] = explode(',', $data['role_ids']);
        }
        if (($key = array_search(config('mine_admin.admin_role'), $data['role_ids'])) !== false) {
            unset($data['role_ids'][$key]);
        }
        if (! empty($data['post_ids']) && ! is_array($data['post_ids'])) {
            $data['post_ids'] = explode(',', $data['post_ids']);
        }
        if (! empty($data['dept_ids']) && ! is_array($data['dept_ids'])) {
            $data['dept_ids'] = explode(',', $data['dept_ids']);
        }
        return $data;
    }
}