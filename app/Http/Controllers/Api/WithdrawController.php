<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\WithdrawRepository;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, WithdrawRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }

    public function transferBalance(Request $request) {

        $transferBalance = $this->repository->transferBalance($request->all());
        if ((isset($transferBalance['success'])) && (($transferBalance['success'] == false))) {
            return $this->response->respondCustomError($transferBalance['message']);
        }

        return $this->response->respond(['data' => [
            'stats' => $transferBalance['stats'],
            'transfer_balance' => $transferBalance['transfer_balance']
        ]]);
    }

    public function transferBalanceDetail(Request $request) {
        $transferBalanceDetail = $this->repository->transferBalanceDetail($request->all());
        if ((isset($transferBalanceDetail['success'])) && (($transferBalanceDetail['success'] == false))) {
            return $this->response->respondCustomError($transferBalanceDetail['message']);
        }
        return $this->response->respond(['data' => [
            'stats' => $transferBalanceDetail['stats'],
            'transfer_balance' => $transferBalanceDetail['transfer_balance'],
            'bookings'=>$transferBalanceDetail['bookings']
        ]]);
    }

}
