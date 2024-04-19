<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:29
 */
namespace Lany\MineAdmin\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Lany\MineAdmin\Traits\HasDateTimeFormatter;
use Lany\MineAdmin\Traits\PageList;
use Lany\MineAdmin\Traits\UserDataScope;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @property int $id 用户ID，主键
 * @property string $username 用户名
 * @property string $user_type 用户类型：(100系统用户)
 * @property string $nickname 用户昵称
 * @property string $phone 手机
 * @property string $email 用户邮箱
 * @property string $avatar 用户头像
 * @property string $signed 个人签名
 * @property string $dashboard 后台首页类型
 * @property int $status 状态 (1正常 2停用)
 * @property string $login_ip 最后登陆IP
 * @property string $login_time 最后登陆时间
 * @property string $backend_setting 后台设置数据
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 * @property mixed $password 密码
 */
class SystemUser extends Authenticatable implements JWTSubject
{
    use PageList, SoftDeletes, UserDataScope, HasDateTimeFormatter;

    protected $table = 'system_user';

    const SUPER_ADMIN_ID = 1;
    const USER_NORMAL = 1;
    const USER_BAN = 2;
    protected $hidden = ['password', 'deleted_at'];
    /**
     * Notes:通过用户名检索用户.
     * User: Lany
     * DateTime: 2024/4/9 13:37
     * @param string $username
     * @return Model|Builder
     */
    public function checkUserByUsername(string $username): Model | Builder
    {
        return self::query()->where('username', $username)->firstOrFail();
    }

    public function isSuperAdmin(): bool
    {
        return config('mine_admin.super_admin_id', self::SUPER_ADMIN_ID) == $this->id;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SystemRole::class, 'system_user_role', 'user_id', 'role_id');
    }


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['dept_id']) && filled($params['dept_id']) && is_string($params['dept_id'])) {
            $tablePrefix = config('database.connections.'.env('DB_CONNECTION').'.prefix');
            $query->selectRaw(DB::raw("DISTINCT {$tablePrefix}system_user.*"))
                ->join('system_user_dept as dept', 'system_user.id', '=', 'dept.user_id')
                ->whereIn(
                    'dept.dept_id',
                    SystemDept::query()
                        ->where(function ($query) use ($params) {
                            $query->where('id', '=', $params['dept_id'])
                                ->orWhere('level', 'like', $params['dept_id'] . ',%')
                                ->orWhere('level', 'like', '%,' . $params['dept_id'])
                                ->orWhere('level', 'like', '%,' . $params['dept_id'] . ',%');
                        })
                        ->pluck('id')
                        ->toArray()
                );
        }
        if (isset($params['username']) && filled($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }
        if (isset($params['nickname']) && filled($params['nickname'])) {
            $query->where('nickname', 'like', '%' . $params['nickname'] . '%');
        }
        if (isset($params['phone']) && filled($params['phone'])) {
            $query->where('phone', '=', $params['phone']);
        }
        if (isset($params['email']) && filled($params['email'])) {
            $query->where('email', '=', $params['email']);
        }
        if (isset($params['status']) && filled($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['filterSuperAdmin']) && filled($params['filterSuperAdmin'])) {
            $query->whereNotIn('id', [env('SUPER_ADMIN')]);
        }

        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }

        if (isset($params['userIds']) && filled($params['userIds'])) {
            $query->whereIn('id', $params['userIds']);
        }

        if (isset($params['showDept']) && filled($params['showDept'])) {
            $isAll = $params['showDeptAll'] ?? false;

            $query->with(['depts' => function ($query) use ($isAll) {
                /* @var Builder $query */
                $query->where('status', SystemDept::ENABLE);
                return $isAll ? $query->select(['*']) : $query->select(['id', 'name']);
            }]);
        }

        if (isset($params['role_id']) && filled($params['role_id'])) {
            $tablePrefix = env('DB_PREFIX');
            $query->whereRaw(
                "id IN ( SELECT user_id FROM {$tablePrefix}system_user_role WHERE role_id = ? )",
                [$params['role_id']]
            );
        }

        if (isset($params['post_id']) && filled($params['post_id'])) {
            $tablePrefix = env('DB_PREFIX');
            $query->whereRaw(
                "id IN ( SELECT user_id FROM {$tablePrefix}system_user_post WHERE post_id = ? )",
                [$params['post_id']]
            );
        }

        return $query;
    }


    public function refresh(): string
    {
        return JWTAuth::refresh();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getId(): int
    {
        return $this->id;
    }
}