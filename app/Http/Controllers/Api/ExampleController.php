<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ResponseCode;
use App\Http\Validators\ExampleValidate;
use App\Services\UserService;
use Illuminate\Http\Request;

/**
 * 使用案例
 *
 * Class ExampleController
 * @package App\Http\Controllers\Api
 */
class ExampleController extends CController
{
    /**
     * 手动依赖注入用户服务
     *
     * @param UserService $userService
     */
    public function example1(UserService $userService)
    {
        $userService->example();
    }

    /**
     * 通过服务管理器优雅的链式调用服务对象
     *
     * 注：服务管理器采用的容器的单例模式进行管理
     */
    public function example2()
    {
        services()->userService->example();
    }

    /**
     * 调用控制器自身提供的验证器
     *
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function example3(Request $request)
    {
        $this->validate($request, [
            'article_id' => 'required|Integer|min:0',
            'class_id' => 'required|Integer|min:0',
            'title' => 'required|max:255',
            'content' => 'required',
            'md_content' => 'required',
        ]);
    }

    /**
     * 依赖注入自定义的验证器
     *
     * @param Request $request
     * @param ExampleValidate $exampleValidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function example4(Request $request, ExampleValidate $exampleValidate)
    {
        // 验证请求数据
        if (!$exampleValidate->scene('delete3')->check($request->all())) {
            return $this->fail($exampleValidate->getError(),[], ResponseCode::VALIDATION);
        }
    }

    public function test()
    {

    }
}
