<?php

namespace App\Models\Rbac;


use App\Models\BaseModel;

/**
 * Class AdminPermission
 *
 * @property int $admin_id 管理员ID
 * @property int $permission_id 权限ID
 *
 * @package App\Models\Rbac
 */
class AdminPermission extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'admin_permissions';
}
