<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Exceptions\ResponseCode;

/**
 * 控制器基类（前后端共用）
 *
 * Class Controller
 * @package App\Http\Controllers
 */
class Controller extends BaseController
{
    use ResponseTrait;

    /**
     * 授权方式
     *
     * @var string
     */
    protected $guard;

    /**
     * 自定义失败的验证响应
     *
     * @param Request $request 请求类
     * @param array $errors 验证器错误信息
     * @return mixed
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return call_user_func(static::$responseBuilder, $request, $errors);
        }

        return $this->fail(array_shift($errors)[0], [], ResponseCode::VALIDATION);
    }

    /**
     * 判断当前请求用户是否登录
     *
     * @return bool
     */
    protected function isLogin()
    {
        return auth($this->guard)->guest() === false;
    }
}
