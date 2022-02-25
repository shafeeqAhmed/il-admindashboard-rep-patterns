<?php

namespace App\Http\Middleware\ApiValidator;



use App\Http\Responses\Responses\ApiResponse;
use App\ValidateRequest\ValidateRequest;
use Closure;
use Illuminate\Http\Request;

class ApiValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public $response ='';
    public function __construct(ApiResponse $response){

       $this->response = $response;
    }

    public function handle(Request $request, Closure $next,$name)
    {

        $validateRequest = new ValidateRequest();
        if(method_exists($validateRequest,$name)){
           $validationResponse = $validateRequest->validate($request,$validateRequest->$name($request->all())['rules'],$validateRequest->$name($request->all())['messages']);

            if($validationResponse->fails()){
                $message = $validationResponse->errors()->all();
                foreach($message as $msg){
                    return $this->response->respondValidationFails($msg);
                }

            }
        }
        return $next($request);
    }

}
