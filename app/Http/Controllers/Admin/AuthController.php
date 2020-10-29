<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ResponseCode;
use App\Helpers\Tree;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Admin
 */
class AuthController extends CController
{
    /**
     * 登录接口
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        // 处理其它登录业务逻辑
        $admin = services()->adminService->login($request->only(['username', 'password']));

        // 通过用户信息换取用户token
        if (!$admin || !$token = auth($this->guard)->login($admin)) {
            return $this->fail('账号不存在或密码填写错误...', [], ResponseCode::AUTH_LOGON_FAIL);
        }

        // 更新登录信息
        $admin->last_login_time = date('Y-m-d H:i:s');
        $admin->last_login_ip = $request->getClientIp();
        $admin->save();

        return $this->success([
            'auth' => $this->formatToken($token),
            'admin_info' => [
                'username' => $admin->username,
                'email' => $admin->email,
                'avatar' => $admin->avatar,
            ]
        ]);
    }

    /**
     * 退出登录接口
     *
     * @return JsonResponse
     */
    public function logout()
    {
        if ($this->isLogin()) {
            auth($this->guard)->logout();
        }

        return $this->success([], 'Successfully logged out');
    }

    /**
     * 刷新授权Token
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->success($this->formatToken(auth($this->guard)->refresh()));
    }

    /**
     * 格式话Token数据
     *
     * @param string $token 授权token
     * @return array
     */
    private function formatToken($token)
    {
        $ttl = auth($this->guard)->factory()->getTTL();
        $expires_time = time() + $ttl * 60;

        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_time' => date('Y-m-d H:i:s', $expires_time)
        ];
    }

    /**
     * 获取授权菜单配置
     */
    public function menus(){
        $adminInfo = $this->user();
        $menus = services()->rbacService->getAuthMenus($adminInfo);
        $perms = services()->rbacService->getAuthPerms($adminInfo);

        $tree = new Tree();
        $tree->init([
            'array'=>$menus,
        ]);

        $menus = $tree->getTreeArray(0);
        return $this->success([
            'menus'=>getMenuTree($menus),
            'perms'=>$perms
        ]);
    }
}
