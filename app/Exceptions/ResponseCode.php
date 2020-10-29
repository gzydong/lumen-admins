<?php

namespace App\Exceptions;

/**
 * 响应状态码
 *
 * Class ResponseCode
 * @package App\Exceptions
 */
class ResponseCode
{
    // 全局响应状态码
    const SUCCESS                = 200;   // 接口处理成功
    const FAIL                   = 305;   // 接口处理失败
    const AUTHORIZATION_FAIL     = 10001; // 授权验证失败
    const AUTHENTICATE_FAIL      = 10002; // 权限验证失败
    const METHOD_NOT_ALLOW       = 10003; // 请求方式不正确
    const RESOURCE_NOT_FOUND     = 10004; // 请求资源找不到
    const VALIDATION             = 10005; // 请求数据验证失败
    const SYSTEM_ERROR           = 10009; // 系统错误

    // 登录响应状态码
    const AUTH_LOGON_FAIL        = 20001; // 授权登失败

    // 注册失败响应码
    const REGISTER_FAIL          = 20002; // 手机号注册失败
}
