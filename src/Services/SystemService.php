<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/19 16:58
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Support\Collection;
use Lany\MineAdmin\Model\MineModel;

abstract class SystemService
{
    public string $model;
    public bool $filterAdminRole = false;

    /**
     * Notes:获取分页列表
     * User: Lany
     * DateTime: 2024/4/19 17:06
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getPageList(?array $params = null, bool $isScope = true): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        return app($this->model)->getPageList($params, $isScope);
    }

    /**
     * Notes:获取列表
     * User: Lany
     * DateTime: 2024/4/19 17:06
     * @param array|null $params
     * @param bool $isScope
     * @return array
     */
    public function getList(?array $params = null, bool $isScope = true): array
    {
        if ($this->filterAdminRole) {
            $params['filterAdminRole'] = true;
        }

        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return app($this->model)->getList($params, $isScope);
    }

    /**
     * 获取树列表.
     */
    public function getTreeList(?array $params = null, bool $isScope = true): array
    {
        $params = array_merge(['orderBy' => 'sort', 'orderType' => 'desc'], $params);
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $params['recycle'] = false;
        return app($this->model)->getTreeList($params, $isScope);
    }

    /**
     * 数组数据转分页数据显示.
     */
    public function getArrayToPageList(?array $params = [], string $pageName = 'page'): array
    {
        $collect = $this->handleArraySearch(collect($this->getArrayData($params)), $params);

        $pageSize = MineModel::PAGE_SIZE;
        $page = 1;

        if ($params[$pageName] ?? false) {
            $page = (int) $params[$pageName];
        }

        if ($params['pageSize'] ?? false) {
            $pageSize = (int) $params['pageSize'];
        }

        $data = $collect->forPage($page, $pageSize)->toArray();

        return [
            'items' => $this->getCurrentArrayPageBefore($data, $params),
            'pageInfo' => [
                'total' => $collect->count(),
                'currentPage' => $page,
                'totalPage' => ceil($collect->count() / $pageSize),
            ],
        ];
    }
    /**
     * 数组当前页数据返回之前处理器，默认对key重置.
     */
    protected function getCurrentArrayPageBefore(array &$data, array $params = []): array
    {
        sort($data);
        return $data;
    }

    /**
     * 数组数据搜索器.
     * @param Collection $collect
     * @param array $params
     * @return Collection
     */
    protected function handleArraySearch(Collection $collect, array $params): Collection
    {
        return $collect;
    }

    /**
     * 设置需要分页的数组数据.
     */
    protected function getArrayData(array $params = []): array
    {
        return [];
    }
}