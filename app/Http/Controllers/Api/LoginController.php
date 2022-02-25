<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\LoginRepository;
use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;

class LoginController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $response = "";
    public $loginRepository;

    public function __construct(ApiResponse $response, LoginRepository $loginRepository) {
        $this->response = $response;
        $this->loginRepository = $loginRepository;
    }

    public function index(Request $request) {
        try {
            return $this->response->respond(['data' => [
                            'users' => $this->userRepository->getAllUser()
            ]]);
        } catch (Exception $ex) {
            return ExceptionHelper::returnAndSaveExceptions($ex, $request);
        }
    }

    public function login(Request $request) {
        $loginResponse = $this->loginRepository->loginUser($request->all());
        if ((isset($loginResponse['success']))) {
//            $this->response->setErrorMessages($loginResponse['message']);
            return $this->response->respondCustomError($loginResponse['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $loginResponse
        ]]);
    }

    public function logout(Request $request){
        $this->loginRepository->logout($request->all());
        return $this->response->respond([]);
    }

    public function verifyCode(Request $request) {
        $verifyCode = $this->loginRepository->verifyCode($request->all());
        if ((isset($verifyCode['success']))) {
//            $this->response->setErrorMessages($verifyCode['message']);
            return $this->response->respondCustomError($verifyCode['message']);
        }
        return $this->response->respond(['data' => [
                        'user' => $verifyCode
        ]]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }



}
