<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * AuthController 控制器分组
 */
$router->group([], function () use ($router) {
    // 授权登录接口
    $router->post('auth/login', ['middleware' => [], 'uses' => 'AuthController@login']);

    // 退出登录接口
    $router->get('auth/logout', ['middleware' => [], 'uses' => 'AuthController@logout']);

    // 会员注册接口
    $router->post('auth/register', ['middleware' => [], 'uses' => 'AuthController@register']);
});

/**
 * ExampleController 控制器分组
 */
$router->group([], function () use ($router) {
    // 案例接口
    $router->get('example/example4', ['middleware' => [], 'uses' => 'ExampleController@example4']);
    $router->get('example/test', ['middleware' => [], 'uses' => 'ExampleController@test']);
});
