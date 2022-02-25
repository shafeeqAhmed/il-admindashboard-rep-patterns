<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Models\User;
use App\Models\Withdraw;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, WithdrawRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function transferPaymentDetail($uuid) {
        $transfer_payment = (new Withdraw())->where('withdraw_uuid',$uuid)->first();
        $user = (new User())->where('id',$transfer_payment->user_id)->first();
        $transfer_payment_detail = $this->repository->getTransferredPaymentDetail($transfer_payment->id);

       return view('pages.booking.transfer-payment-detail',compact('transfer_payment_detail', 'transfer_payment','user'));
    }

    public function availeableTransactions($uuid) {
        $user = (new UserRepository)->getBoatOwnerDetail('user_uuid',$uuid);
        $stats = $this->repository->getOwnerBookingStats($uuid);
        $transactions = $this->repository->getAvailableTransactions($uuid);

        return view('pages.booking.ownerAvailableTransactions',compact('user', 'stats', 'transactions'));
    }

    public function saveAvaileableTransactions(Request $request){
        try {
            DB::beginTransaction();
            $inputs = $request->except('_token');
            $this->repository->saveAvailableTransactions($inputs);
            DB::commit();
            return redirect()->back()->with('success_message', "Successfully Saved !");
        } catch (\Exception $ex){
            DB::rollBack();
            return redirect()->back()->with('error_message', $ex->getMessage());
        }
    }
}
