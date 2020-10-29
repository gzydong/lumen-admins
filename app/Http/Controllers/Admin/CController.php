<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

class CController extends Controller
{
    /**
     * 授权守卫
     *
     * @var string
     */
    protected $guard = 'admin';

    /**
     * 获取登录用户信息
     *
     * @return Admin|null
     */
    protected function user()
    {
        return auth($this->guard)->user();
    }
}
