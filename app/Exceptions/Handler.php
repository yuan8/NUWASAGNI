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
        $mes=[
            "404"=>"Not Found (page or other resource doesn’t exist)",
            "401"=>"Not authorized (not logged in)",
            "403"=>"Logged in but access to requested area is forbidden",
            "400"=>"Bad request (something wrong with URL or parameters)",
            "422"=>"Unprocessable Entity (validation failed)",
            "500"=>"General server error"
        ];

        if("This action is unauthorized."==$exception->getMessage()){

        }

        $code= $this->isHttpException($exception) ? $exception->getStatusCode() : 99910;
       
       
       if($code!=99910){
        if(in_array($code, array_keys($mes))){
            return response()->view('errors.index',['code'=>$code,
                'message'=>(isset($mes[$code])?$mes[$code]:'')],500);
        }
       }


      
        return parent::render($request, $exception);
    }
}
