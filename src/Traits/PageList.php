<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/11 09:46
 */

namespace Lany\MineAdmin\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

trait PageList
{
    use UserDataScope;
    public const PAGE_SIZE = 15;

    public function getList(?array $params, bool $isScope = true): array
    {
        return $this->listQuerySetting($params, $isScope)->get()->toArray();
    }

    /*
     * 获取数据列表(带分页)
     */
    public function getPageList(?array $params = [], bool $isScope = true, string $pageName = 'page'): array
    {
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        $paginate = $this->listQuerySetting($params, $isScope)->paginate(
            (int) ($params['pageSize'] ?? self::PAGE_SIZE),
            ['*'],
            $pageName,
            (int) ($params[$pageName] ?? 1)
        );
        return $this->setPaginate($paginate, $params);
    }

    /**
     * 设置数据库分页.
     */
    public function setPaginate(LengthAwarePaginator $paginate, array $params = []): array
    {
        return [
            'items' => method_exists($this, 'handlePageItems') ? $this->handlePageItems($paginate->items(), $params) : $paginate->items(),
            'pageInfo' => [
                'total' => $paginate->total(),
                'currentPage' => $paginate->currentPage(),
                'totalPage' => $paginate->lastPage(),
            ],
        ];
    }

    /**
     * 返回模型查询构造器.
     */
    public function listQuerySetting(?array $params, bool $isScope): Builder
    {
        $query = (($params['recycle'] ?? false) === true) ? self::onlyTrashed() : self::query();
        $isScope && $query->userDataScope();
        if ($params['select'] ?? false) {
            $query->select($this->filterQueryAttributes($params['select']));
        }

        $query = $this->handleOrder($query, $params);

        //$isScope && $query->userDataScope();

        return $this->handleSearch($query, $params);
    }
    /**
     * 过滤查询字段不存在的属性.
     */
    public function filterQueryAttributes(array $fields, bool $removePk = false): array
    {
        $model = new self();
        $attrs = $model->getFillable();
        foreach ($fields as $key => $field) {
            if (! in_array(trim($field), $attrs) && mb_strpos(str_replace('AS', 'as', $field), 'as') === false) {
                unset($fields[$key]);
            } else {
                $fields[$key] = trim($field);
            }
        }
        if ($removePk && in_array($model->getKeyName(), $fields)) {
            unset($fields[array_search($model->getKeyName(), $fields)]);
        }
        $model = null;
        return (count($fields) < 1) ? ['*'] : $fields;
    }

    /**
     * 排序处理器.
     */
    public function handleOrder(Builder &$query, ?array &$params = null): Builder
    {
        // 对树型数据强行加个排序
        if (isset($params['_mineadmin_tree'])) {
            $query->orderBy($params['_mineadmin_tree_pid']);
        }

        if ($params['orderBy'] ?? false) {
            if (is_array($params['orderBy'])) {
                foreach ($params['orderBy'] as $key => $order) {
                    $query->orderBy($order, $params['orderType'][$key] ?? 'asc');
                }
            } else {
                $query->orderBy($params['orderBy'], $params['orderType'] ?? 'asc');
            }
        }

        return $query;
    }

    /**
     * 搜索处理器.
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        return $query;
    }

    /**
     * 获取树列表.
     */
    public function getTreeList(
        ?array $params = null,
        bool $isScope = true,
        string $id = 'id',
        string $parentField = 'parent_id',
        string $children = 'children'
    ): array {
        $params['_mineadmin_tree'] = true;
        $params['_mineadmin_tree_pid'] = $parentField;
        $data = $this->listQuerySetting($params, $isScope)->get();
        return $data->toTree([], $data[0]->{$parentField} ?? 0, $id, $parentField, $children);
    }


}