<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return bool|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($result = $this->renderJson($exception)) {
            return $result;
        }

        return parent::render($request, $exception);
    }

    /**
     * 自定义响应错误信息
     *
     * @param Throwable $exception
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function renderJson(Throwable $exception)
    {
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->error(ResponseCode::METHOD_NOT_ALLOW, "The server returned a '405 Method Not Allowed'.", [], 405);
        } else if ($exception instanceof NotFoundHttpException) {
            return $this->error(ResponseCode::RESOURCE_NOT_FOUND, "The server returned a '404 Not Found'.", [], 404);
        } else if ($exception instanceof AuthorizationException) {
            return $this->error(ResponseCode::AUTHORIZATION_FAIL, $exception->getMessage(), [], 401);
        } else if ($exception instanceof HttpException) {
            return $this->error(ResponseCode::AUTHENTICATE_FAIL, $exception->getMessage(), [], 403);
        } else {
            // ... 自定义其它响应信息
        }

        return false;
    }
}
