<?php

namespace App\Services;

use Illuminate\Container\Container;

/**
 * Class Service
 *
 * 服务处理层（通过链式操作简化手动注入依赖）
 * 案例： services()->userService->example()
 *
 * @property UserService $userService 会员用户服务
 * @property RbacService $rbacService 权限管理服务
 * @property AdminService $adminService 管理员服务
 *
 * @package App\Services
 */
class Service
{
    /**
     * 服务列表(需要手配置)
     *
     * @var array
     */
    private $childService = [
        'userService' => UserService::class,
        'rbacService' => RbacService::class,
        'adminService' => AdminService::class,
    ];

    /**
     * Service constructor.
     */
    public function __construct()
    {

    }

    /**
     * 魔术方法
     *
     * @param $attr
     * @return mixed|object
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __get($attr)
    {
        if (!isset($this->childService[$attr])) {
            throw new \InvalidArgumentException('Child Service [' . $attr . '] is not find in ' . get_called_class() . ', you must config it! ');
        }

        if (!Container::getInstance()->has($this->childService[$attr])) {
            Container::getInstance()->singleton($this->childService[$attr]);
        }

        return Container::getInstance()->make($this->childService[$attr]);
    }
}
