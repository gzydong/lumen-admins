<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class CController extends Controller
{
    /**
     * 授权守卫
     *
     * @var string
     */
    protected $guard = 'api';

    /**
     * 获取登录用户信息
     *
     * @return User|null
     */
    protected function user()
    {
        return auth($this->guard)->user();
    }
}
