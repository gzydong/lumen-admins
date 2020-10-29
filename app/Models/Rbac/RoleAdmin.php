<?php

namespace App\Models\Rbac;

use App\Models\BaseModel;

/**
 * App\Models\Rbac\RoleAdmin
 *
 * @property int $admin_id 管理员用户ID
 * @property int $role_id 角色ID
 *
 * @package App\Models\Rbac
 */
class RoleAdmin extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'role_admin';

    protected $primaryKey = 'admin_id';

    protected $fillable = ['role_id','admin_id'];
}
