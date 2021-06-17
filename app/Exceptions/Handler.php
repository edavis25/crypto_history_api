<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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
        if ($request->wantsJson()) {
            if ($exception instanceof HttpException) {
                $message = "";
                if ($exception->getStatusCode() === 400) {
                    $message = 'Bad request.';
                } elseif ($exception->getStatusCode() === 401) {
                    $message = 'Unauthorized.';
                } elseif ($exception->getStatusCode() === 403) {
                    $message = 'Forbidden.';
                } elseif ($exception->getStatusCode() === 404) {
                    $message = 'Resource not found.';
                }

                return response()->json([
                    'code'  => $exception->getStatusCode(),
                    'message' => $message ?: $exception->getMessage() ?: 'Something went wrong.',
                ], $exception->getStatusCode());
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'code' => 422,
                    'message' => $exception->getMessage(),
                    'errors' => $exception->errors()
                ], 422);
            }
        }

        return parent::render($request, $exception);
    }
}
