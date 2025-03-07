<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;


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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function unauthenticated ($request, AuthenticationException $exception)
    {   
        if ($request->expectsJson()) {
            return response()->json(['message'=>$exception->getMessage()], 401);
        };

        $guard = Arr::get($exception->guards(), 0);
        
        switch($guard) {
            case 'admin':
                $login = "admin.login";
            break;
            case 'web':
                $login = "login";
            break;
            case 'default':
                $login = "login";
            break;
        }

        return redirect()->guest(route($login));
    }
}
