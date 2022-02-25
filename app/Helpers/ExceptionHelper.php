<?php

namespace App\Helper;



use App\Models\CodeException;
use App\Http\Responses\Responses\ApiResponse;
Class ExceptionHelper {
    /*
      |--------------------------------------------------------------------------
      | ExceptionHelper that contains all the exception related methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use exception processes
      |
     */

/**
     * Description of ExceptionHelper
     *
     * @author ILSA Interactive
     */

    /**
     * returnAndSaveExceptions method
     * @param type $exception
     * @return type
     */
    public static function returnAndSaveExceptions($exception, $request = null) {
        $lang = $request->input('lang');
        $language = !empty($lang) ? $lang : 'EN';
//        $exception_details['exception_uuid'] = UuidHelper::generateUniqueUUID("exceptions", "exception_uuid");
        $exception_details['exception_uuid'] = 'pakista';
        $exception_details['exception_file'] = $exception->getFile();
        $exception_details['exception_line'] = $exception->getLine();
        $exception_details['exception_message'] = $exception->getMessage();
        $exception_details['exception_url'] = $request->url();
        $exception_details['exception_code'] = $exception->getCode();
//        dd($exception_details);
        CodeException::saveException($exception_details);
//        dd($exception_details);
        return ApiResponse::setHttpStatus($exception->getCode())->setCustomStatus($exception->getCode())->setErrorMessages($exception->getMessage())->respondWithErrors();
//        return CommonHelper::jsonErrorResponse(MessageHelper::getMessageData('error', $language)['general_error']);
    }

}

?>
