<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;

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

        // Some exceptions don't have a message
        $exception_message = (!empty($exception->getMessage()) ? trim($exception->getMessage()) : 'App Error Exception');

        // Log message
        $log_message = "\"" . $exception_message . " in file '" . $exception->getFile() . "' on line '" . $exception->getLine() . "'" . "\"";

        if (!config('app.debug')) {
            Log::error($log_message);
        } else {
            parent::report($exception);
        }
        /* if (!config('app.debug')) {
            Log::error('['.$exception->getCode().'] "'.$exception->getMessage().'" on line '.$exception->getTrace()[0]['line'].' of file '.$exception->getTrace()[0]['file']);
        } else {
            parent::report($exception);
        } */
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
        return $this->handleApiException($request, $exception);
        /* if ($request->wantsJson() || $request->ajax()) {   //add Accept: application/json in request
            return $this->handleApiException($request, $exception);
        }
        return parent::render($request, $exception); */
    }
    /**
     * Handle api response
     * 
     * TODO: ModelNotFound exception???
     *
     * @param \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return void
     */
    private function handleApiException($request, Exception $exception)
    {
        $exception = $this->prepareException($exception);
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
            return $this->customApiValidationResponse($exception);
        }

        if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * Get response message
     *
     * @param int $status_code
     * @param array $exception
     * @return  \Illuminate\Http\Response
     */
    private function customApiValidationResponse($exception)
    {
        $response = [];
        $response['status'] = 422;
        $response['message'] = $exception->original['message'];
        $response['errors'] = $exception->original['errors'];
        return response()->json($response, $response['status']);
    }

    /**
     * Handle custom api response
     *
     * @param \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $status_code = $exception->getStatusCode();
        } else {
            $status_code = 500;
        }

        $response = $this->getMessage($status_code, $exception);
        // If the app is in debug mode
        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $status_code;

        return response()->json($response, $status_code);
    }
    /**
     * Get response message
     *
     * @param int $status_code
     * @param Exception $exception
     * @return array
     */
    private function getMessage($status_code, Exception  $exception): array
    {
        $response = [];
        $message = ($status_code == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();

        if (\Lang::has('httpstatus.' . $status_code)) {
            //$message = trans('httpstatus.'.$status_code).'. '. $exception->getMessage();
            $message = trans('httpstatus.' . $status_code);

            if (config('app.debug')) {
                $message .= '. '. $exception->getMessage();
            }
        }

        $response['message'] = $message;
        return  $response;
    }
}
