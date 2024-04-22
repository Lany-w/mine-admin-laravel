<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 17:23
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DataMaintainService extends SystemService
{
    /**
     * 获取表状态分页列表.
     */
    public function getPageList(?array $params = [], bool $isScope = true): array
    {
        if (env('DB_CONNECTION') !== 'mysql') {
            return [];
        }
        return $this->getArrayToPageList($params);
    }

    /**
     * 数组数据搜索器.
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params): Collection
    {
        if ($params['name'] ?? false) {
            $collect = $collect->filter(function ($row) use ($params) {
                return Str::contains($row->Name, $params['name']);
            });
        }
        if ($params['engine'] ?? false) {
            $collect = $collect->where('Engine', $params['engine']);
        }
        return $collect;
    }

    /**
     * 数组当前页数据返回之前处理器，默认对key重置.
     */
    protected function getCurrentArrayPageBefore(array &$data, array $params = []): array
    {
        $tables = [];
        foreach ($data as $item) {
            $tables[] = array_change_key_case((array) $item);
        }
        return $tables;
    }

    /**
     * 设置需要分页的数组数据.
     */
    protected function getArrayData(array $params = []): array
    {
        return DB::select(Db::raw("SHOW TABLE STATUS WHERE name NOT LIKE '%migrations'")->getValue());
    }
}