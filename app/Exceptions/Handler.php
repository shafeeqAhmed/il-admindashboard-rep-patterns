<?php

namespace App\Exceptions;

use App\Http\Responses\Responses\ApiResponse;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Repositories\ExceptionRepository;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */

    public function register()
    {
        $this->renderable(function (Throwable $e) {
//            $this->handleException();
        });
    }
    public function report(Throwable $e)
    {
        //store exception in database
        (new ExceptionRepository)->createException($e);
        return parent::report($e); // TODO: Change the autogenerated stub
    }

    public function render($request, Throwable $exception)
    {
//        Log::error('Http Exception', [
//            'exception' => $exception
//        ]);
//        if ($this->isHttpException($exception)) {
//            switch ($exception->getStatusCode()) {
//                // not authorized
//                case '403':
//                    return self::errorResponse("Request Forbidden",403);
//                    break;
//                // not found
//                case '404':
//                    return  self::errorResponse("URL not found",404);
//                    break;
//                // internal error
//                case '500':
//                    return  self::errorResponse("Internal server error occured",500);
//                    break;
//                default:
//                    return  self::errorResponse("Handler has returned an error",502);
//                    break;
//            }
//        }else {
//            return  self::errorResponse("Something is going wrong we are working on it",503);
//
//        }
        return parent::render($request, $exception); // TODO: Change the autogenerated stub
    }

    public function errorResponse($message,$code) {
       return (new ApiResponse)->setHttpStatus($code)
            ->setCustomStatus($code)
            ->setErrorMessages($message)
            ->respondWithErrors();

    }
}
