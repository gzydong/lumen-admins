<?php

namespace App\Models;

use App\Models\Rbac\Permission;
use App\Models\Rbac\RoleAdmin;
use App\Models\Rbac\RolePermission;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Admin Model
 *
 * @property integer $id 管理ID
 * @property string $username 管理员登录账号/登录名
 * @property string $password 登录密码
 * @property integer $status 账号状态[0:已禁用;10:正常;]
 * @property string $avatar 管理员头像
 * @property string $email 管理员邮箱
 * @property string $nickname 昵称
 * @property string $last_login_time 最后一次登录时间
 * @property string $last_login_ip 最后一次登录IP
 * @property integer $is_delete 是否删除
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 *
 * @package App\Models
 */
class Admin extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * @var string 定义表名字
     */
    protected $table = 'admins';

    // 管理员状态
    const STATUS_ENABLES = 10; // 正常状态
    const STATUS_DISABLES = 0; // 禁用状态

    // 删除状态
    const YES_DELETE = 1;      // 已删除状态
    const NO_DELETE = 0;      // 未删除状态

    /**
     * 获取管理员角色信息(一个管理员只有一个角色)
     *
     * @return mixed
     */
    public function role()
    {
        return RoleAdmin::join('roles', 'roles.id', '=', 'role_admin.role_id')->where('admin_id', $this->id)->first(['roles.*']);
    }

    /**
     * 获得管理员的独立权限
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function perms()
    {
        return $this->belongsToMany(Permission::class, 'admin_permissions', 'admin_id', 'permission_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 设置密码
     *
     * @param string $value 密码值
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * 获取状态信息
     *
     * @param int|null $status 状态值
     * @return array|string
     */
    public static function getStatus($status = null)
    {
        $arr = [
            self::STATUS_ENABLES => '正常状态',
            self::STATUS_DISABLES => '禁用状态'
        ];

        return $status == null ? $arr : (isset($arr[$status]) ? $arr[$status] : '');
    }

    /**
     * 检测管理员是否有权限
     *
     * @param string $route 路由地址
     * @return bool
     */
    public function hasPerms($route)
    {
        // 获取管理员管理角色信息
        $roleInfo = $this->role();

        if ($roleInfo) {
            // 获取角色对应的权限
            $permissions = RolePermission::join('permissions', 'permissions.id', '=', 'role_permission.permission_id')->where('role_permission.role_id', $roleInfo->id)->pluck('permissions.route');
            foreach ($permissions as $permission) {
                if ($route == $permission) return true;
            }
        }

        // 查询管理员独立权限
        $permissions = $this->perms()->pluck('permissions.route');
        foreach ($permissions as $permission) {
            if ($route == $permission) return true;
        }

        return false;
    }

    /**
     * 移除用户的一个角色
     *
     * @param int|array $role
     * @return int
     */
    public function detachRole($role)
    {
        return $this->roles()->detach($role);
    }

    /**
     * 移除用户的所有角色
     *
     * @return int
     */
    public function detachRoleAll()
    {
        return $this->roles()->detach();
    }
}
