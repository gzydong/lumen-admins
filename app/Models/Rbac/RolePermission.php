<?php

namespace App\Models\Rbac;

use App\Models\BaseModel;

/**
 * App\Models\Rbac\RolePermission
 *
 * @property int $permission_id 权限ID
 * @property int $role_id 角色ID
 *
 * @package App\Models\Rbac
 */
class RolePermission extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'role_permission';
}
