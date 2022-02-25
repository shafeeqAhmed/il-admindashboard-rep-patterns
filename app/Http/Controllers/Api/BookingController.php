<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatReviewRepository;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;
use DB;

class BookingController extends Controller {

    public $response = "";
    public $bookingRepository = "";
    public $boatReviewRepository = "";

    public function __construct(
            ApiResponse $response,
            BookingRepository $bookingRepository,
            BoatReviewRepository $boatReviewRepository
    ) {
        $this->response = $response;
        $this->bookingRepository = $bookingRepository;
        $this->boatReviewRepository = $boatReviewRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

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

    public function getBookingDetail(Request $request) {
        return $this->response->respond(["data" => [
            'booking_detail' => $this->bookingRepository->getBookingDetail($request->booking_uuid)
        ]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateBookingRequest(Request $request) {
        DB::beginTransaction();
        $updateBooking = $this->bookingRepository->updateBooking($request->all());
        if ((isset($updateBooking['success'])) && (($updateBooking['success'] == false))) {
            return $this->response->respondCustomError($updateBooking['message']);
        }
        return $this->response->respond(["data" => [
                        'booking_detail' => $updateBooking
        ]]);
    }

    public function updateStatus(Request $request) {
        $updateBooking = $this->bookingRepository->updateStatus($request->all());
        if ((isset($updateBooking['success'])) && (($updateBooking['success'] == false))) {
            return $this->response->respondCustomError($updateBooking['message']);
        }


        return $this->response->respond(["data" => [
                        'booking_detail' => $updateBooking
        ]]);
    }

    public function rescheduleBooking(Request $request){
        $reschedule_time = $this->bookingRepository->rescheduleBooking($request->all());
        return $this->response->respond(["data" => [
            'booking_detail' => $reschedule_time
        ]]);
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
