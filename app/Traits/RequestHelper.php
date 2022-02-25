<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/28/2021
 * Time: 10:08 AM
 */

namespace App\Traits;


trait RequestHelper
{
    public function requestType()
    {
        $routeParts = explode('/', request()->route()->getPrefix());
        if(isset($routeParts[0]) && $routeParts[0] == 'api')
            return 'api';

        return 'web';
    }

    /*
     * incoming request is an api request or not?
     */
    public function isApi()
    {
        return ($this->requestType() == 'api')?true:false;
    }

    /*
     * incoming request is a web request or not?
     */
    public function isWeb()
    {
        return (!$this->isApi())?true: false;
    }

    /*
     * returns web/api Authenticator for the request.
     */
    public function getRequestAuthenticator()
    {
        return ($this->isApi())?$this->apiAuthenticatorWithToken(): new WebAuthenticator();
    }

    /*
     * returns apiAuthenticator with Authorization
     * key...
     */
    public function apiAuthenticatorWithToken()
    {
        $authenticator = new ApiAuthenticator();
        $authenticator->setAccessToken($this->getAccessToken());
        return $authenticator;
    }

    /*
     * return Authorization token within the
     * incoming request.
     */
    public function getAccessToken()
    {
        // before running unit tests...
       // $headers['Authorization'] = '$2y$10$tSM.PiN9BnMfyonqjHlwTONa1DPHbyQSAMOtmt4chJYXenGeYySHC';
        $headers = apache_request_headers();
        return (isset($headers['access_token']))?$headers['access_token']:null;
    }
}