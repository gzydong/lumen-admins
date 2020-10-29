<?php

namespace App\Repositorys;

use App\Models\Admin;
use App\Traits\PagingTrait;

/**
 * Class AdminRepository
 *
 * @package App\Repositorys
 */
class AdminRepository
{
    use PagingTrait;

    /**
     * 通过用户名查询管理员信息
     *
     * @param string $username
     * @return mixed
     */
    public function findByUserName(string $username)
    {
        return Admin::where('username', $username)->first();
    }

    /**
     * 获取管理员列表
     *
     * @param int $page 分页数
     * @param int $page_size 分页大小
     * @param array $params 查询参数
     * @return array
     */
    public function findAllAdmins(int $page, int $page_size, array $params = [])
    {
        $rowObj = Admin::select(['id', 'username', 'nickname', 'email', 'avatar', 'status', 'last_login_time', 'last_login_ip', 'created_at', 'updated_at']);

        $orderBy = 'id';
        $sort = 'desc';
        if (isset($params['order_by'], $params['sort'])) {
            $orderBy = $params['order_by'];
            $sort = $params['sort'];
        }

        if (isset($params['username']) && !empty($params['username'])) {
            $rowObj->where('username', 'like', "%{$params['username']}%");
        }

        if (isset($params['status'])) {
            $rowObj->where('status', $params['status']);
        }

        $rowObj->where('is_delete', Admin::NO_DELETE);

        $total = $rowObj->count();
        $rows = $rowObj->orderBy($orderBy, $sort)->forPage($page, $page_size)->get()->toArray();
        return $this->getPagingRows($rows, $total, $page, $page_size);
    }
}
