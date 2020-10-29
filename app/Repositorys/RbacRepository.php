<?php

namespace App\Repositorys;

use Exception;
use App\Models\Rbac\Role;
use App\Models\Rbac\Permission;
use App\Models\Rbac\RolePermission;
use App\Traits\PagingTrait;

class RbacRepository
{
    use PagingTrait;

    /**
     * 添加角色信息
     *
     * @param array $data 角色信息
     * @return bool
     */
    public function insertRole(array $data)
    {
        try {
            $result = new Role();
            $result->name = $data['name'];
            $result->display_name = $data['display_name'];
            $result->description = $data['description'];
            $result->created_at = date('Y-m-d H:i:s');
            $result->updated_at = date('Y-m-d H:i:s');
            return $result->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 更新角色信息
     *
     * @param int $role_id 角色ID
     * @param array $data 角色数据
     * @return mixed
     */
    public function updateRole(int $role_id, array $data)
    {
        try {
            return Role::where('id', $role_id)->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 删除角色信息
     *
     * @param int $role_id 角色信息
     * @return boolean
     */
    public function deleteRole(int $role_id)
    {
        return Role::where('id', $role_id)->delete();
    }

    /**
     * 获取角色信息
     *
     * @param int $role_id 角色ID
     * @param array $filed 查询字段
     * @return Role|null
     */
    public function findByRoleId(int $role_id, $filed = ['*'])
    {
        return Role::where('id', $role_id)->first($filed);
    }

    /**
     * 查询角色列表
     *
     * @param int $page 分页数
     * @param int $page_size 分页大小
     * @param array $params 查询参数
     * @return array
     */
    public function findAllRoles(int $page, int $page_size, array $params = [])
    {
        $rowObj = Role::select(['id', 'name', 'display_name', 'description', 'created_at', 'updated_at']);

        if (isset($params['display_name']) && !empty($params['display_name'])) {
            $rowObj->where('display_name', 'like', "%{$params['display_name']}%");
        }

        $total = $rowObj->count();

        $rows = $rowObj->orderBy('id', 'desc')->forPage($page, $page_size)->get()->toArray();
        return $this->getPagingRows($rows, $total, $page, $page_size);
    }

    /**
     * 获取角色权限列表
     *
     * @param int $role_id 角色ID
     * @return array
     */
    public function findRolePermsIds(int $role_id)
    {
        return RolePermission::where('role_id', $role_id)->pluck('permission_id')->toArray();
    }

    /**
     * 添加权限信息
     *
     * @param array $data 权限信息
     * @return bool|Permission
     */
    public function insertPerms(array $data)
    {
        $data = $this->filterParams($data);
        try {
            $result = new Permission();
            $result->parent_id = $data['parent_id'];
            $result->type = $data['type'];
            $result->title = $data['title'];
            $result->path = $data['path'];
            $result->component = $data['component'];
            $result->perms = $data['perms'];
            $result->icon = $data['icon'];
            $result->sort = $data['sort'];
            $result->hidden = $data['hidden'];
            $result->is_frame = $data['is_frame'];
            $result->created_at = date('Y-m-d H:i:s');
            $result->updated_at = date('Y-m-d H:i:s');
            return $result->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 过滤数据
     *
     * @param array $data
     * @return array
     */
    private function filterParams(array $data){
        if($data['type'] == Permission::TYPE_DIR){
            $data['is_frame'] = 0;
            $data['component'] = '';
            $data['perms'] = '';
        }else if($data['type'] == Permission::TYPE_MENU){
            $data['perms'] = '';
        }else{
            $data['is_frame'] = 0;
            $data['component'] = '';
            $data['path'] = '';
            $data['icon'] = '';
        }
        return $data;
    }

    /**
     * 修改权限信息
     *
     * @param int $permission_id 权限ID
     * @param array $data 权限数据
     * @return mixed
     */
    public function updatePerms(int $permission_id, array $data)
    {
        $data = $this->filterParams($data);

        try {
            $data['updated_at'] = date('Y-m-d H:i:s');
            return Permission::where('id', $permission_id)->update($data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 删除权限信息
     *
     * @param int $permission_id 权限ID
     * @return mixed
     */
    public function deletePerms(int $permission_id)
    {
        if (Permission::where('parent_id', $permission_id)->exists()) {
            return false;
        }

        return Permission::where('id', $permission_id)->delete();
    }

    /**
     * 获取权限列表
     *
     * @param array $field 查询字段
     * @return array
     */
    public function findAllPerms($field = ['*'])
    {
        return Permission::orderBy('sort','asc')->get($field)->toArray();
    }
}
