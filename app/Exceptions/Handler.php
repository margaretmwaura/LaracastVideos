<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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


    public function report(Exception $exception)
    {
        parent::report($exception);
    }


    public function render($request, Exception $exception)
    {
        if($exception instanceof ValidationException)
        {
            if ($request->expectsJson()) {
                return response('Sorry, validation failed.', 422);
            }
        }
        if($exception instanceof ThrottleException)
        {
            return response('You are posting too frequently',422);
        }
        return parent::render($request, $exception);
    }
}
