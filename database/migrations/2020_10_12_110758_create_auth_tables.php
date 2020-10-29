<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

//https://www.cnblogs.com/eleven24/p/9380514.html
//https://my.oschina.net/u/3372402/blog/4417940
class CreateAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rolesTable = 'roles';
        $roleUserTable = 'role_admin';
        $permissionsTable = 'permissions';
        $permissionRoleTable = 'role_permission';
        $adminPermissions = 'admin_permissions';

        $userModel = new \App\Models\Admin();
        $userKeyName = $userModel->getKeyName();
        $usersTable = $userModel->getTable();


        Schema::create($rolesTable, function (Blueprint $table) {
            $table->increments('id')->comment('角色ID');
            $table->string('name')->unique()->comment('角色名');
            $table->string('display_name')->nullable()->comment('角色显示名称');
            $table->string('description')->nullable()->comment('角色描述');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });

        Schema::create($roleUserTable, function (Blueprint $table) use ($userKeyName, $rolesTable, $usersTable) {
            $table->bigInteger('admin_id')->unsigned()->unique()->comment('管理员用户ID');
            $table->integer('role_id')->unsigned()->comment('角色ID');

            $table->foreign('admin_id')->references($userKeyName)->on($usersTable)->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on($rolesTable)->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['admin_id', 'role_id']);

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });

        Schema::create($permissionsTable, function (Blueprint $table) {
            $table->increments('id')->comment('权限ID');
            $table->unsignedInteger('pid')->default(0)->comment('父级权限ID');
            $table->tinyInteger('type')->default(0)->comment('权限类型[0:目录;1:菜单;2:权限;]');
            $table->string('route')->unique()->comment('权限路由');
            $table->string('display_name')->nullable()->comment('权限显示名称');
            $table->string('description')->nullable()->comment('权限描述');
            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->comment('修改时间');

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });

        Schema::create($permissionRoleTable, function (Blueprint $table) use ($permissionsTable, $rolesTable) {
            $table->integer('role_id')->unsigned()->comment('角色ID');
            $table->integer('permission_id')->unsigned()->comment('权限ID');

            $table->foreign('role_id')->references('id')->on($rolesTable)->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on($permissionsTable)->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['permission_id', 'role_id']);

            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->engine = 'InnoDB';
        });

        Schema::create($adminPermissions, function (Blueprint $table) use ($permissionsTable) {
            $table->unsignedBigInteger('admin_id')->comment('管理员ID');
            $table->integer('permission_id')->unsigned()->comment('权限ID');

            $table->foreign('permission_id')->references('id')->on($permissionsTable)->onDelete('cascade');
            $table->primary(['permission_id', 'admin_id'], 'permission_id_admin_id');
        });

        $prefix = DB::getConfig('prefix');
        DB::statement("ALTER TABLE `{$prefix}{$rolesTable}` comment 'RBAC - 角色表'");
        DB::statement("ALTER TABLE `{$prefix}{$roleUserTable}` comment 'RBAC - 角色、用户关联表'");
        DB::statement("ALTER TABLE `{$prefix}{$permissionsTable}` comment 'RBAC - 权限表'");
        DB::statement("ALTER TABLE `{$prefix}{$permissionRoleTable}` comment 'RBAC - 角色、权限关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('roles');
        Schema::drop('role_admin');
        Schema::drop('role_permission');
        Schema::drop('permissions');
        Schema::drop('admin_permissions');
    }
}
