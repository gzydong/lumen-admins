<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App\Models
 */
class BaseModel extends Model
{
    /**
     * @var array 批量赋值的黑名单
     */
    protected $guarded = ['id'];

    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;
}
