<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResponseCode;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class AuthController extends CController
{
    /**
     * 用户服务层
     *
     * @var UserService
     */
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        // 授权中间件
        $this->middleware("auth:{$this->guard}", [
            // 不用进行登录验证的方法
            'except' => ['login', 'register']
        ]);
    }

    /**
     * 会员手机号登录接口
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // 数据验证
        $this->validate($request, [
            'mobile' => "required|regex:/^1[345789][0-9]{9}$/",
            'password' => 'required'
        ]);

        // 自定义 Jwt Token 参数
        $claims = ['platform' => 'app'];

        // 登录
        $token = auth($this->guard)->claims($claims)->attempt($request->only(['mobile', 'password']));
        if (!$token) {
            return $this->fail('账号不存在或密码填写错误...', [], ResponseCode::AUTH_LOGON_FAIL);
        }

        // ... 自定义其它业务

        return $this->success([
            'Authentication' => $this->formatToken($token),
            'user_info' => '',
        ]);
    }

    /**
     * 会员手机号注册接口
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        // 数据验证
        $this->validate($request, [
            'mobile' => 'required|regex:/^1[345789][0-9]{9}$/',
            'password' => 'required'
        ]);

        [$isTrue, $message,] = $this->userService->register($request);
        if (!$isTrue) {
            return $this->fail($message, [], ResponseCode::REGISTER_FAIL);
        }

        return $this->success([], '账号注册成功...');
    }

    /**
     * 会员退出接口
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth($this->guard)->logout();

        return $this->success([], 'Successfully logged out');
    }

    /**
     * 刷新授权token
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->success($this->formatToken(auth($this->guard)->refresh()));
    }

    /**
     * 格式化Token数据
     *
     * @param string $token 授权token
     * @return array
     */
    protected function formatToken($token)
    {
        $ttl = auth($this->guard)->factory()->getTTL();
        $expires_time = time() + $ttl * 60;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_time' => $expires_time
        ];
    }
}
