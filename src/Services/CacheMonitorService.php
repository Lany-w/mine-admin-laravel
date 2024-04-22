<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/22 17:07
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Support\Facades\Redis;

class CacheMonitorService
{
    public function getCacheServerInfo(): array
    {
        $info = Redis::info();

        $iterator = null;
        $keys = [];
        do{
            $params = [$iterator, config('database.redis.options.prefix').'*', 100];
            $key = Redis::connection('cache')->command('SCAN', $params);
            $keys = array_merge($keys, $key);
        } while($iterator != 0);

        return [
            'keys' => &$keys,
            'server' => [
                'version' => &$info['redis_version'],
                'redis_mode' => ($info['redis_mode'] === 'standalone') ? '单机' : '集群',
                'run_days' => &$info['uptime_in_days'],
                'aof_enabled' => ($info['aof_enabled'] == 0) ? '关闭' : '开启',
                'use_memory' => &$info['used_memory_human'],
                'port' => &$info['tcp_port'],
                'clients' => &$info['connected_clients'],
                'expired_keys' => &$info['expired_keys'],
                'sys_total_keys' => count($keys),
            ],
        ];
    }

}