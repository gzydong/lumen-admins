<?php

namespace App\Repositorys;

use App\Models\User;

/**
 * Class UserRepository
 *
 * @package App\Repositorys
 */
class UserRepository
{
    /**
     * 通过用户ID查询用户信息
     *
     * @param int $user_id 用户ID
     * @param array $field 数据字段
     * @return User|null
     */
    public function findById($user_id, $field = ['*'])
    {
        return User::where('id', $user_id)->first($field);
    }

    /**
     * 通过手机号查询用户信息
     *
     * @param string $mobile 手机号
     * @param array $field 数据字段
     * @return User|null
     */
    public function findByMobile($mobile,$field = ['*']){
        return User::where('mobile', $mobile)->first($field);
    }
}
