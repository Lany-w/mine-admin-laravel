<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/23 10:52
 */

namespace Lany\MineAdmin\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id 主键
 * @property int $api_id api ID
 * @property string $api_name 接口名称
 * @property string $access_name 接口访问名称
 * @property string $request_data 请求数据
 * @property string $response_code 响应状态码
 * @property string $response_data 响应数据
 * @property string $ip 访问IP地址
 * @property string $ip_location IP所属地
 * @property string $access_time 访问时间
 * @property string $remark 备注
 */
class SystemApiLog extends MineModel
{
    public $timestamps = false;

    protected $table = 'system_api_log';

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        if (isset($params['api_name']) && filled($params['api_name'])) {
            $query->where('api_name', 'like', '%' . $params['api_name'] . '%');
        }
        if (isset($params['ip']) && filled($params['ip'])) {
            $query->where('ip', 'like', '%' . $params['ip'] . '%');
        }
        if (isset($params['access_name']) && filled($params['access_name'])) {
            $query->where('access_name', 'like', '%' . $params['access_name'] . '%');
        }
        if (isset($params['access_time']) && filled($params['access_time']) && is_array($params['access_time']) && count($params['access_time']) == 2) {
            $query->whereBetween(
                'access_time',
                [$params['access_time'][0] . ' 00:00:00', $params['access_time'][1] . ' 23:59:59']
            );
        }
        return $query;
    }
}