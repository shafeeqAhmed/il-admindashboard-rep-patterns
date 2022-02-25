<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\WalletRepository;
use App\Traits\Responses\WalletResponse;
use Illuminate\Http\Request;
use DB;

class WalletController extends Controller {

    protected $response = '';
    protected $walletRepository = '';

    public function __construct(ApiResponse $response, WalletRepository $walletRepository) {
        $this->response = $response;
        $this->walletRepository = $walletRepository;
    }

    public function getTransactions(Request $request) {
        $transactions = $this->walletRepository->getTransactions($request->all());
        if ((isset($transactions['success'])) && (($transactions['success'] == false))) {
            return $this->response->respondCustomError($transactions['message']);
        }
        return $this->response->respond(['data' => [
                        'transactions' => $transactions
        ]]);
    }
    public function getTransactionDetail(Request $request) {
        $transaction = $this->walletRepository->getTransactionDetail($request->all());
        if ((isset($transactions['success'])) && (($transaction['success'] == false))) {
            return $this->response->respondCustomError($transaction['message']);
        }
        return $this->response->respond(['data' => [
                        'transaction_detail' => $transaction
        ]]);
    }

    public function getBalance(Request $request) {
        $balance = $this->walletRepository->getBalance($request->all());
        if ((isset($balance['success'])) && (($balance['success'] == false))) {
            return $this->response->respondCustomError($balance['message']);
        }
        return $this->response->respond(['data' => [
                        'balance' => $balance
        ]]);
    }

    public function getPendingTransactions(Request $request) {
        $transactions = $this->walletRepository->getPendingTransactions($request->all());
        if ((isset($transactions['success'])) && (($transactions['success'] == false))) {
            return $this->response->respondCustomError($transactions['message']);
        }
        return $this->response->respond(['data' => [
                        'pending_transactions' => $transactions
        ]]);
    }


    public function addBankDetail(Request $request) {
        DB::beginTransaction();
        $data = $this->walletRepository->addBankDetail($request->all());
        if ((isset($data['success'])) && (($data['success'] == false))) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(['data' => [
                        'bank_detail' => $data
        ]]);
    }

    public function getBankDetail(Request $request) {
        $data = $this->walletRepository->getBankDetail($request->all());
        if ((isset($data['success'])) && (($data['success'] == false))) {
            return $this->response->respondCustomError($data['message']);
        }
        return $this->response->respond(['data' => [
                        'bank_detail' => $data
        ]]);
    }

}
