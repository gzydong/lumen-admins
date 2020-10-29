<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class RbacMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * 授权守卫
     *
     * @var string
     */
    public $guard = 'admin';

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next)
    {
//        // 判断访问用户是否登录
//        if ($this->auth->guard($this->guard)->guest()) {
//            throw new AuthorizationException('未授权登录，禁止访问...');
//        }
//
//        // 获取登录用户信息
//        $admin = $this->auth->guard($this->guard)->user();
//
//        // 判断是否有访问权限(admin 跳过权限验证)
//        if ($admin->username !== 'admin' && !$admin->hasPerms($request->path())) {
//            throw new HttpException(403, '当前登录用户，暂无访问权限!!!');
//        }

        return $next($request);
    }
}
