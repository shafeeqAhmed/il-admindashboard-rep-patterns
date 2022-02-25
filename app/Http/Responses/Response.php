<?php

/**
 * Created by PhpStorm.
 * User: waqas
 * Date: 3/16/2016
 * Time: 1:46 PM
 */

namespace App\Http\Responses;

use App\Traits\RequestHelper;

abstract class Response {

    use RequestHelper;

    public $CUSTOM_STATUS = 0;
    public $HTTP_STATUS = 200;
    public $ERROR_MESSAGES = [];

    public function setHttpStatus($status) {
        $this->HTTP_STATUS = $status;
        return $this;
    }

    public function getHttpStatus() {
        return $this->HTTP_STATUS;
    }

    public function setCustomStatus($status) {
        $this->CUSTOM_STATUS = $status;
        return $this;
    }

    public function getCustomStatus() {
        return $this->CUSTOM_STATUS;
    }

    public function setErrorMessages($messages) {
        $this->ERROR_MESSAGES = $messages;
        return $this;
    }

    public function getErrorMessages() {
        return $this->ERROR_MESSAGES;
    }

    public function respondNotFound($messages = ["record not found"]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInternalServerError($messages = ["Something went wrong with the server!"]) {
        return $this->setHttpStatus(500)->setCustomStatus(505)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondValidationFails($messages = ["Your request did not passed our server requirements!"]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->apiErrorValidationResponse();
    }

    public function respondAuthenticationFailed($messages = ["Dear user you are not logged in."]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInvalidCredentials($messages = ["Invalid username or password"]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondAccessTokenNotProvided($messages = ["Session expired, please login again."]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondInvalidAccessToken($messages = ["Session expired, please login again."]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondOwnershipConstraintViolation($messages = ["Ownership Constraint Violation."]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->respondWithErrors();
    }

    public function respondCustomError($messages = ["Error occurred in this request"]) {
        return $this->setHttpStatus(404)->setCustomStatus(404)->setErrorMessages($messages)->apiCustomErrorResponse();
    }

    public function respondWithErrors() {
        if ($this->isWeb())
            return $this->webErrorResponse();
        else
            return $this->apiErrorResponse();
    }

    public function apiErrorResponse() {
        return $this->respond([
                    'status' => 0,
                    'error' => [
                        'messages' => $this->getErrorMessages(),
                        'code' => $this->getCustomStatus(),
                        'http_status' => $this->getHttpStatus(),
                    ],
                    'data' => []
        ]);
    }

    public function apiErrorValidationResponse() {
        return $this->respondValidation([
                    'success' => false,
                    'message' => $this->getErrorMessages(),
        ]);
    }

    public function apiCustomErrorResponse() {
        return $this->respondValidation([
                    'success' => false,
                    'message' => $this->getErrorMessages(),
        ]);
    }

    public function webErrorResponse() {
        return $this->redirectBackWithErrors()->withInput();
    }

    public function redirectBack() {
        return redirect()->back();
    }

    public function redirectBackWithErrors() {
        \Session::flash('errors', $this->getErrorMessages());
        return $this->redirectBack();
    }

}
