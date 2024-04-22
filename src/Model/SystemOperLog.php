<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 13:25
 */

namespace Lany\MineAdmin\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id 主键
 * @property string $username 用户名
 * @property string $method 请求方式
 * @property string $router 请求路由
 * @property string $service_name 业务名称
 * @property string $ip 请求IP地址
 * @property string $ip_location IP所属地
 * @property string $request_data 请求数据
 * @property string $response_code 响应状态码
 * @property string $response_data 响应数据
 * @property int $created_by 创建者
 * @property int $updated_by 更新者
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property string $remark 备注
 */
class SystemOperLog extends MineModel
{
    protected $table = 'system_oper_log';


    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['ip']) && filled($params['ip'])) {
            $query->where('ip', $params['ip']);
        }
        if (isset($params['service_name']) && filled($params['service_name'])) {
            $query->where('service_name', 'like', '%' . $params['service_name'] . '%');
        }
        if (isset($params['username']) && filled($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }
        if (isset($params['created_at']) && filled($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) == 2) {
            $query->whereBetween(
                'created_at',
                [$params['created_at'][0] . ' 00:00:00', $params['created_at'][1] . ' 23:59:59']
            );
        }
        return $query;
    }
}