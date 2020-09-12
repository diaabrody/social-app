<?php

namespace App\Exceptions;

use App\ApiCode;
use App\Http\Controllers\ApiResponse\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $result = $this->handleException($request  , $exception);
        return  $result;
    }

    public function handleException($request ,$exception){
        if ($exception instanceof  ValidationException){
            return  $this->respondWithValidationError($exception->errors());
        }
        if ($exception instanceof AuthenticationException){
            $code = ApiCode::UN_AUTHENTICATED;
            return $this->respondUnAuthorizedRequest($code , ApiCode::$statusTexts[$code]);
        }
        if($exception instanceof  AuthorizationException){
            $code = ApiCode::INVALID_CREDENTIALS;
            return $this->respondUnAuthorizedRequest($code , ApiCode::$statusTexts[$code]);
        }
        if ($exception instanceof MethodNotAllowedHttpException){
            return  $this->respondWithMethodNotAllowedError();
        }
        if ($exception instanceof NotFoundHttpException){
            return $this->respondWithHttpNotFoundError();
        }
        if ($exception instanceof HttpException){
            $statusCode = $exception->getStatusCode();
            return $this->respondWithError($statusCode , $statusCode , $exception->getMessage());
        }
        return $this->respondWithUnexpectedError('Unexpected Error Occurred');
    }
}
