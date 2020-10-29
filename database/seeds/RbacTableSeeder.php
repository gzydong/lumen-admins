<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbacTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            1 => [
                'route' => 'admin/rbac/create-role',
                'display_name' => '创建角色',
                'description' => '-',
            ],
            2 => [
                'route' => 'admin/rbac/edit-role',
                'display_name' => '编辑角色',
                'description' => '-',
            ],
            3 => [
                'route' => 'admin/rbac/delete-role',
                'display_name' => '删除角色',
                'description' => '-',
            ],
            4 => [
                'route' => 'admin/rbac/create-permission',
                'display_name' => '创建权限',
                'description' => '-',
            ],
            5 => [
                'route' => 'admin/rbac/edit-permission',
                'display_name' => '编辑权限',
                'description' => '-',
            ],
            6 => [
                'route' => 'admin/rbac/delete-permission',
                'display_name' => '删除权限',
                'description' => '-',
            ], 7 => [
                'route' => 'admin/rbac/give-role-permission',
                'display_name' => '分配角色权限',
                'description' => '-',
            ], 8 => [
                'route' => 'admin/rbac/give-admin-permission',
                'display_name' => '分配管理员角色及权限',
                'description' => '-',
            ],
        ];

        foreach ($permissions as $id => $permission) {
            DB::table('permissions')->insert([
                'id' => $id,
                'route' => $permission['route'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
