<?php
/*
|--------------------------------------------------------------------------
| Application Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * AuthController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->post('auth/login', ['uses' => 'AuthController@login']);

    // 退出登录接口
    $router->post('auth/logout', ['uses' => 'AuthController@logout']);

    $router->get('auth/menus', ['uses' => 'AuthController@menus']);
});

/**
 * AccountController 控制器分组
 */
$router->group([], function () use ($router) {
    $router->get('account/detail', ['uses' => 'AccountController@detail']);
    $router->post('account/update-password', ['uses' => 'AccountController@updatePassword']);
    $router->post('account/update-account', ['uses' => 'AccountController@updateAccount']);
});

/**
 * AdminsController 控制器分组
 */
$router->group(['middleware'=>['rbac']], function () use ($router) {
    $router->post('admins/create', ['uses' => 'AdminsController@create']);
    $router->post('admins/delete', ['uses' => 'AdminsController@delete']);
    $router->post('admins/update-password', ['uses' => 'AdminsController@updatePassword']);
    $router->post('admins/update-status', ['uses' => 'AdminsController@updateStatus']);
    $router->get('admins/lists', ['uses' => 'AdminsController@lists']);
});

/**
 * RbacController 控制器分组
 */
$router->group([], function () use ($router) {
    // 角色相关接口
    $router->post('rbac/create-role', ['uses' => 'RbacController@createRole']);
    $router->post('rbac/edit-role', ['uses' => 'RbacController@editRole']);
    $router->post('rbac/delete-role', ['uses' => 'RbacController@deleteRole']);
    $router->get('rbac/roles', ['uses' => 'RbacController@roles']);

    // 权限相关接口
    $router->post('rbac/create-permission', ['uses' => 'RbacController@createPermission']);
    $router->post('rbac/edit-permission', ['uses' => 'RbacController@editPermission']);
    $router->post('rbac/delete-permission', ['uses' => 'RbacController@deletePermission']);
    $router->get('rbac/permissions', ['uses' => 'RbacController@permissions']);

    // 分配角色权限
    $router->post('rbac/give-role-permission', ['uses' => 'RbacController@giveRolePermission']);
    $router->post('rbac/give-admin-permission', ['uses' => 'RbacController@giveAdminPermission']);
    $router->get('rbac/get-role-permission', ['uses' => 'RbacController@getRolePerms']);


    $router->get('rbac/get-admin-permission', ['uses' => 'RbacController@getAdminPerms']);
});
