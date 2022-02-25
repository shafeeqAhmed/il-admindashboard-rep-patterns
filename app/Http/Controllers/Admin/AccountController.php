<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;

class AccountController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, AccountRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    public function earning() {
        $records = (new UserRepository())->getBoatOwners();
//        dd($records);
        return view('pages.revenue.index',compact('records'));
    }
}
