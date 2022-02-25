<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller {

    protected $response = "";
    protected $paymentRepository = "";

    public function __construct(
            ApiResponse $response,
            PaymentRepository $PaymentRepository
    ) {

        $this->response = $response;
        $this->paymentRepository = $PaymentRepository;
    }

    public function getRequestToken(Request $request) {
        return $this->response->respond(["data" => [
                        'token' => $this->paymentRepository->getRequestToken($request->all())
        ]]);
    }

    public function getCardsList(Request $request) {
        $data = $this->paymentRepository->getCardsList($request->all());
        if ((isset($data['success'])) && ($data['success'] == false)) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(["data" => [
                        'cards' => $data
        ]]);
    }

    public function saveAuthorizationData(Request $request) {
        $data = $this->paymentRepository->saveAuthorizationData($request->all());
        if ((isset($data['success'])) && ($data['success'] == false)) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(["data" => [
                        'authorized' => $data
        ]]);
    }

    public function deleteCard(Request $request) {
        $data = $this->paymentRepository->deleteCard($request->all());
        if ((isset($data['success'])) && ($data['success'] == false)) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(["data" => [
                        'cards' => $data
        ]]);
    }

    public function transactionFeedback(Request $request) {
        $data = $this->paymentRepository->transactionFeedback($request->all());
        if ((isset($data['success'])) && ($data['success'] == false)) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(["data" => [
                        'response' => $data
        ]]);
    }

    public function paymentNotification(Request $request) {
        $data = $this->paymentRepository->paymentNotification($request->all());
        if ((isset($data['success'])) && ($data['success'] == false)) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(["data" => [
                        'response' => $data
        ]]);
    }

}
