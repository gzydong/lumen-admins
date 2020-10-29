<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;

class AdminsController extends CController
{
    /**
     * 添加管理员账号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'password2' => 'required',
        ]);

        $result = services()->adminService->create($request);
        if (!$result) {
            return $this->fail('管理员账号添加失败...');
        }

        return $this->success([], '管理员账号添加成功...');
    }

    /**
     * 删除管理员账号
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request)
    {
        $this->validate($request, [
            'admin_id' => 'required|integer:min:1'
        ]);

        $admin_id = $request->input('admin_id');
        if ($this->user()->id == $admin_id || !services()->adminService->delete($admin_id)) {
            return $this->fail('管理员账号删除失败...');
        }

        return $this->success([], '管理员账号删除成功...');
    }

    /**
     * 修改指定管理员登录密码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|integer:min:1',
            'password' => 'required',
            'password2' => 'required|same:password',
        ]);

        $result = services()->adminService->updatePassword(
            $request->input('id'),
            $request->input('password')
        );

        if (!$result) {
            return $this->fail('管理员密码修改失败...');
        }

        return $this->success([], '管理员密码已修改...');
    }

    /**
     * 修改管理员账户状态
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateStatus(Request $request)
    {
        $this->validate($request, [
            'admin_id' => 'required|integer:min:1',
            'status' => 'required|in:0,1',
        ]);

        // 状态映射
        $arr = [
            '0' => Admin::STATUS_ENABLES,
            '1' => Admin::STATUS_DISABLES
        ];

        $result = services()->adminService->updateStatus(
            $request->input('admin_id'),
            $arr[$request->input('status')]
        );

        if (!$result) {
            return $this->fail('管理员状态修改失败');
        }

        return $this->success([], '管理员状态修改成功...');
    }


    /**
     * 获取管理员列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function lists(Request $request)
    {
        $this->validate($request, [
            'page' => 'required|integer:min:1',
            'page_size' => 'required|in:10,20,30,50,100',
            'status' => 'in:0,1,2',
        ]);

        $result = services()->adminService->getAdmins($request);
        return $this->success($result);
    }
}
