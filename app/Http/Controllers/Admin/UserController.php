<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BookingRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, UserRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    public function dashboard(Request $request) {

//        if($type == 'active') {
//            $where = ['is_active'=>1];
//
//        }elseif($type == 'not_verified') {
//            $where = ['is_verified'=>0];
//        }elseif($type == 'blocked') {
//            $where = ['status'=>'blocked'];
//
//        }elseif($type == 'deleted') {
//            $where = ['status'=>'deleted'];
//
//        }

        $data['active_boaters'] = $this->repository->getUserCount('boat_owner','active');
        $data['not_verified_boaters'] = $this->repository->getUserCount('boat_owner','not_verified');
        $data['blocked_boaters'] = $this->repository->getUserCount('boat_owner','blocked');
        $data['deleted_boaters'] = $this->repository->getUserCount('boat_owner','deleted');

        $data['active_customers'] = $this->repository->getUserCount('customer','active');
        $data['not_verified_customers'] = $this->repository->getUserCount('customer','not_verified');
        $data['blocked_customers'] = $this->repository->getUserCount('customer','blocked');
        $data['deleted_customers'] = $this->repository->getUserCount('customer','deleted');


        $data['pending_booking'] = $this->repository->getBookingCount('pending');
        $data['confirmed_booking'] = $this->repository->getBookingCount('confirmed');
        $data['completed_booking'] = $this->repository->getBookingCount('completed');
        $data['cancelled_booking'] = $this->repository->getBookingCount('cancelled');
        $data['rejected_booking'] = $this->repository->getBookingCount('rejected');
        $data['pending_payment_booking'] = $this->repository->getBookingCount('pending_payment');

        $data['approved_boats'] = $this->repository->getBoatCount('is_approved',1);
        $data['un_approved_boats'] = $this->repository->getBoatCount('is_approved',0);

        $data['reported_posts'] = $this->repository->getReportedPost();

        $data['blocked_posts'] = $this->repository->getBlockedPost();

        return view('pages.dashboard',compact('data'));
    }
    public function index(Request $request) {
        if(Auth::check()) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin-login-form');
        }
    }
    public function adminLoginForm(Request $request) {
        return view('pages.login');
    }

    public function adminLogin(Request $request) {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        $credentials['role'] = 'admin';
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
//            return redirect()->route('admin.dashboard');
            return redirect()->intended('dashboard');
        }
        return back()->with([
            'error_message' => 'The provided credentials do not match our records.',
        ]);
    }
    public function adminLogout(){
        Auth::logout();
        return redirect()->route('admin-login');
    }
    public function messageCodes() {
        $records = $this->repository->messageCodes();
        return view('pages.setting.message-codes',compact('records'));
    }

    public function bounceAndComplaints(Request $request) {
        $data = $request->getContent();
        \Log::channel('customLogs')->info($data);
        $dataInArray = json_decode($data,true);
        \Log::channel('customLogs')->info($dataInArray);
        $responseData = $dataInArray;
        return $this->repository->blackEmail($responseData);
       
    }
}
