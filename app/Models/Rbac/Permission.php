<?php

namespace App\Models\Rbac;

use App\Models\BaseModel;

/**
 * App\Models\Rbac\Permission
 *
 * @property int $id
 * @property string $parent_id 父节点权限ID
 * @property string $type 权限类型[0:目录;1:菜单;2:权限]
 * @property string $title 菜单名称
 * @property string $path 路由地址
 * @property string $component 组件地址
 * @property string $perms 权限标识
 * @property string $icon 图标
 * @property string $sort 排序
 * @property integer $hidden 排序
 * @property integer $is_frame 是否外链
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @package App\Models\Rbac
 */
class Permission extends BaseModel
{
    /**
     * @var string 定义表名字
     */
    protected $table = 'permissions';

    // 权限类型
    const TYPE_DIR = 0;
    const TYPE_MENU = 1;
    const TYPE_PERMS = 2;

    /**
     * Many-to-Many relations with role model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }
}
