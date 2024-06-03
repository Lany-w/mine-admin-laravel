<?php
/**
 * Notes:
 * User: Lany
 * DateTime: 2024/4/12 11:09
 */

namespace Lany\MineAdmin\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Lany\MineAdmin\Exceptions\MineException;
use Lany\MineAdmin\Exceptions\NormalStatusException;
use Lany\MineAdmin\Mine;
use Lany\MineAdmin\Model\SystemDept;

class SystemDeptService extends SystemService
{
    public string $model = SystemDept::class;


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

    /**
     * 获取部门领导列表.
     */
    public function getLeaderList(?array $params = null): array
    {
        return app($this->model)->getLeaderList($params);
    }

    /**
     * 新增部门领导
     */
    public function addLeader(array  $data): bool
    {
        $users = [];
        foreach ($data['users'] as $item) {
            $users[] = array_merge(['created_at' => date('Y-m-d H:i:s')], $item);
        }
        $id = (int) $data['id'];
        $model = $this->model::find($id, ['id']);
        foreach ($users as $key => $user) {
            if (Db::table('system_dept_leader')->where('dept_id', $id)->where('user_id', $user['user_id'])->exists()) {
                unset($users[$key]);
            }
        }
        count($users) > 0 && $model->leader()->sync($users, false);
        return true;
    }

    /**
     * 删除部门领导
     */
    public function delLeader(array $data): bool
    {
        $users = [];
        foreach ($data['ids'] ?? [] as $id) {
            $users[] = ['user_id' => $id];
        }
        $model = $this->model::find($data['id'], ['id']);

        count($users) > 0 && $model->leader()->detach($users);
        return true;
    }

    /**
     * 处理数据.
     */
    protected function handleData(array $data): array
    {
        $pid = $data['parent_id'] ?? 0;

        if (isset($data['id']) && $data['id'] == $pid) {
            throw new NormalStatusException(t('system.parent_dept_error'), 500);
        }

        if ($pid === 0) {
            $data['level'] = $data['parent_id'] = '0';
        } else {
            $data['level'] = $this->read($data['parent_id'])->level . ',' . $data['parent_id'];
        }

        return $data;
    }
}