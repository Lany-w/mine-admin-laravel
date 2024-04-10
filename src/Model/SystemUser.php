<?php

/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/9 13:29
 */
namespace Lany\MineAdmin\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
    protected $table = 'system_user';
    const USER_NORMAL = 1;
    const USER_BAN = 2;
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
}