<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 11:09
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Model\SystemDept;

class SystemDeptService
{

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
        return app(SystemDept::class)->getTreeList($params, $isScope);
    }

    /**
     * 获取前端选择树.
     */
    public function getSelectTree(): array
    {
        $treeData = SystemDept::query()->select(['id', 'parent_id', 'id AS value', 'name AS label'])
            ->where('status', SystemDept::ENABLE)
            ->orderBy('parent_id')
            ->orderBy('sort', 'desc')
            ->userDataScope()
            ->get()->toArray();

        $deptTree = (new Collection())->toTree($treeData, $treeData[0]['parent_id'] ?? 0);

        $user = Mine::guard()->user();

        if (config('mineadmin.data_scope_enabled', true) && !$user->isSuperAdmin()) {
            $deptIds = DB::table(table: 'system_user_dept')->where('user_id', '=', $user->id)->pluck('dept_id');
            $treeData = SystemDept::query()
                ->select(['id', 'parent_id', 'id AS value', 'name AS label'])
                ->whereIn('id', $deptIds)
                ->where('status', SystemDept::ENABLE)
                ->orderBy('parent_id')->orderBy('sort', 'desc')
                ->get()->toArray();

            return (new Collection())->toTree(array_merge($treeData, $deptTree), $treeData[0]['parent_id'] ?? 0);
        }
        return $deptTree;
    }
}