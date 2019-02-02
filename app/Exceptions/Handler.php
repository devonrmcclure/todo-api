<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
		$request->headers->set('Accept', 'application/json');

		if ($exception instanceof NotFoundHttpException) {
			return response()->json([
				'message' => $exception->getMessage() ? $exception->getMessage() : 'Not Found',
				'exception' => 'NotFoundHttpException',
				'status' => $exception->getStatusCode()
			])->setStatusCode($exception->getStatusCode()) ;
		}

		// Doesn't have a getStatusCode() method
		if ($exception instanceof ModelNotFoundException) {
			return response()->json([
				'message' => $exception->getMessage(),
				'exception' => 'ModelNotFoundException',
				'status' => 404
			])->setStatusCode(404);
		}

		if ($exception instanceof MethodNotAllowedHttpException) {
			return response()->json([
				'message' => $exception->getMessage() ? $exception->getMessage() : 'Method not allowed',
				'exception' => 'MethodNotAllowedHttpException',
				'status' => $exception->getStatusCode()
			])->setStatusCode($exception->getStatusCode())->header('Allow', $exception->getHeaders());
		}
		
        return parent::render($request, $exception);
    }
}
