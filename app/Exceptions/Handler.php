<?php

namespace App\Exceptions;

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
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
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
		if ($exception instanceof \Illuminate\Session\TokenMismatchException) {            
			return redirect('/')->withErrors(['token_error' => 'Your session has expired. Please login again']);
		} elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {            
			return redirect('/')->withErrors(['token_error' => 'You are not logged in. Please login']);
		} elseif ($exception instanceof \SAPNWRFC\ConnectionException) {
            abort(404, 'We have a problem with FI integration, please try again later');
        } else {
			//return response()->view('errors.custom', [], 500);
		}

        return parent::render($request, $exception);
    }
	
	//Added by Sherif	
	protected function prepareResponse($request, Exception $e)
	{
		if ($this->isHttpException($e)) {
			return $this->toIlluminateResponse($this->renderHttpException($e), $e);
		} elseif (! config('app.debug')) {
			return response()->view('errors.500', [], 500);
		} else {
			return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
		}
	}
	
	protected function convertExceptionToResponse(Exception $e)
    {
        if (!config('app.debug')) {
            return response()->view('errors.500', [], 500);
        }

        return parent::convertExceptionToResponse($e);
    }
}
