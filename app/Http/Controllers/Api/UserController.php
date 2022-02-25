<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\UserRepository;
use App\Traits\Responses\UserResponse;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller {

    protected $response = '';
    protected $userRepository = '';

    public function __construct(ApiResponse $response, UserRepository $userRepository) {
        $this->response = $response;
        $this->userRepository = $userRepository;
    }

    public function signUp(Request $request) {
        DB::beginTransaction();
        $signUp = $this->userRepository->createUser($request->all());
        if ((isset($signUp['success'])) && ($signUp['success'] == false)) {
//            $this->response->setErrorMessages($signUp['message']);
            return $this->response->respondCustomError($signUp['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $signUp
        ]]);
    }

    public function resetPassword(Request $request) {
        DB::beginTransaction();
        $reset = $this->userRepository->resetPassword($request->all());
        if ((isset($reset['success'])) && ($reset['success'] == false)) {
            return $this->response->respondCustomError($reset['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $reset
        ]]);
    }

    public function forgetPassword(Request $request) {
        DB::beginTransaction();
        $reset = $this->userRepository->forgetPassword($request->all());
        if ((isset($reset['success'])) && ($reset['success'] == false)) {
//            $this->response->setErrorMessages($signUp['message']);
            return $this->response->respondCustomError($reset['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $reset
        ]]);
    }

    public function getCode(Request $request) {
        $getCode = $this->userRepository->getCode($request->all());
        if ((isset($getCode['success'])) && (($getCode['success'] == false))) {
//            $this->response->setErrorMessages($getCode['message']);
            return $this->response->respondCustomError($getCode['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $getCode
        ]]);
    }

    public function getUserBoats(Request $request) {
        return $this->response->respond(["data" => [
                        'boats' => $this->userRepository->getUserBoats($this->userRepository->getIdByUuid('user_uuid', $request->get('user_uuid')))
        ]]);
    }

    public function getUserPersonalInformation(Request $request) {
        return $this->response->respond([
                    'user' => $this->userResponse($this->userRepository->getUser($request->get('user_uuid')))
        ]);
    }

    public function updateUser(Request $request) {


        $result = $this->userRepository->updateUser($request->all());
        //if every thing fine return user response
        if ($result['success']) {
            return $this->response->respond(['data' => [
                            'user' => $result['data']
            ]]);
        }
        //if there is error then throw error
        return $this->response->respondCustomerError($result['message']);
    }

    public function getCountries(Request $request) {
        return $this->response->respond(["data" => [
                        'countries' => $this->userRepository->getCountries()
        ]]);
    }

    public function updateUserImage(Request $request) {
        return $this->response->respond(["data" => [
                        'user' => $this->userRepository->updateUserImage($request->all())
        ]]);
    }


    public function setUserLanguage(Request $request){
        return $this->userRepository->setUserLanguage($request->all());
    }

    public function saveCustomerSupport(Request $request){
        return $this->userRepository->saveSupportQuestionnaire($request->all());
    }

    public function sendMailUser(Request $request){
        return $this->userRepository->sendMailUser($request->all());
    }

}
