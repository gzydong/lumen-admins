<?php

namespace App\Traits;

use App\Exceptions\ResponseCode;
use Illuminate\Http\JsonResponse;

Trait ResponseTrait
{
    /**
     * 处理失败信息返回
     *
     * @param string $message 响应提示
     * @param array $data 响应数据
     * @param int $code 错误码
     *
     * @return JsonResponse
     */
    public function fail($message = 'FAIL', $data = [],$code = ResponseCode::FAIL)
    {
        return response()->json(compact('code', 'message', 'data'), 200);
    }

    /**
     * 处理成功信息返回
     *
     * @param array $data 响应数据
     * @param string $message 响应提示
     *
     * @return JsonResponse
     */
    public function success($data = [], $message = 'OK')
    {
        $code = ResponseCode::SUCCESS;
        return response()->json(compact('code', 'message', 'data'), 200);
    }

    /**
     * 系统错误
     *
     * @param int $code 错误码
     * @param string $message 提示信息，默认为空
     * @param array $data 响应数据
     * @param int $statusCode http 状态码
     *
     * @return JsonResponse
     */
    public function error($code = ResponseCode::SYSTEM_ERROR, $message = '', $data = [], $statusCode = 500)
    {
        return response()->json(compact('code', 'message', 'data'), $statusCode);
    }
}
