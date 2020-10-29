<?php

namespace App\Services;

use Exception;
use App\Models\Admin;
use App\Repositorys\AdminRepository;
use App\Traits\PagingTrait;
use Illuminate\Http\Request;

class AdminService
{
    use PagingTrait;

    /**
     * @var AdminRepository
     */
    protected $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    /**
     * 登录业务处理
     *
     * @param array $params
     * @return bool|Admin
     */
    public function login(array $params)
    {
        // 通过登录名查询用户信息
        $admin = $this->adminRepository->findByUserName($params['username']);
        if (!$admin) {
            return false;
        }

        // 判断账号是否已被删除
        if (Admin::YES_DELETE == $admin->is_delete) {
            return false;
        }

        // 登录密码验证
        if (!check_password($params['password'], $admin->password)) {
            return false;
        }

        // 判断用户状态
        if ($admin->status !== Admin::STATUS_ENABLES) {
            return false;
        }

        return $admin;
    }

    /**
     * 创建管理员账号
     *
     * @param Request $request
     * @return bool
     */
    public function create(Request $request)
    {
        try {
            $admin = new Admin();
            $admin->username = $request->input('username');
            $admin->password = $request->input('password');
            $admin->status = Admin::STATUS_ENABLES;
            $admin->last_login_time = date('Y-m-d H:i:s');
            $admin->created_at = date('Y-m-d H:i:s');
            $admin->updated_at = date('Y-m-d H:i:s');
            return $admin->save();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 修改管理员状态
     *
     * @param int $admin_id 管理员ID
     * @param int $status 账号状态
     * @return bool
     */
    public function updateStatus(int $admin_id, int $status)
    {
        return (bool)Admin::where('id', $admin_id)->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 修改指定管理员密码
     *
     * @param int $admin_id 管理员ID
     * @param string $password 新密码
     * @return bool
     */
    public function updatePassword(int $admin_id, string $password)
    {
        $password = app('hash')->make($password);
        return (bool)Admin::where('id', $admin_id)->update(['password' => $password, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * 获取管理员列表
     *
     * @param Request $request
     * @return mixed
     */
    public function getAdmins(Request $request)
    {
        $params = [];

        $orderBy = $request->only(['sortField', 'sortOrder']);
        if (isset($orderBy['sortField'], $orderBy['sortOrder'])) {
            $params['order_by'] = $orderBy['sortField'];
            $params['sort'] = get_orderby_sort($orderBy['sortOrder']);
        }

        if ($username = $request->input('username', '')) {
            $params['username'] = addslashes($username);
        }

        if ($status = $request->input('status', '')) {
            if (in_array($status, [1, 2])) {
                $arr = [
                    '1' => Admin::STATUS_ENABLES,
                    '2' => Admin::STATUS_DISABLES,
                ];

                $params['status'] = $arr[$status];
            }
        }

        return $this->adminRepository->findAllAdmins(
            $request->input('page', 1),
            $request->input('page_size', 10),
            $params
        );
    }

    /**
     * 删除管理员账号（软删除）
     *
     * @param int $admin_id 管理员ID
     * @return boolean
     */
    public function delete(int $admin_id)
    {
        return (bool)Admin::where('id', $admin_id)->update([
            'is_delete' => Admin::YES_DELETE,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
