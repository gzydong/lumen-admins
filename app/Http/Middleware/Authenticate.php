<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * 后台接口免登录接口路由
     *
     * @var array
     */
    protected $adminGuardArr = [
        'admin/auth/login',
        'admin/auth/logout',
    ];

    /**
     * Create a new middleware instance.
     *
     * @param \Illuminate\Contracts\Auth\Factory $auth
     * @return void
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
     * @param null $guard
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // 判断是否是后台授权验证及过滤白名单
        if ($guard == 'admin' && $this->adminGuardNoLogin($request)) {
            return $next($request);
        }

        if ($this->auth->guard($guard)->guest()) {
            throw new AuthorizationException('未授权登录，禁止访问...');
        }

        return $next($request);
    }

    /**
     * 后台面登录白名单
     *
     * @param Request $request
     * @return boolean
     */
    public function adminGuardNoLogin(Request $request)
    {
        return in_array($request->path(), $this->adminGuardArr);
    }
}
