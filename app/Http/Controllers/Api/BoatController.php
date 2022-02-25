<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Models\Boat;
use App\Models\User;
use App\Repositories\BoatFavoriteRepository;
use App\Repositories\BoatRepository;
use App\Repositories\BookingRepository;
use App\Repositories\CaptainRepository;
use App\Repositories\SystemSettingRepository;
use Arr;
use Illuminate\Http\Request;
use DB;

class BoatController extends Controller {

    protected $response = "";
    protected $boatRepository = "";
    protected $boatLocationRepository = '';
    protected $captainRepository = '';
    protected $bookingRepository = '';
    protected $boatFavoriteRepository = '';

    public function __construct(
            ApiResponse $response,
            BoatRepository $BoatRepository,
            CaptainRepository $captainRepository,
            BookingRepository $bookingRepository,
            BoatFavoriteRepository $boatFavoriteRepository
    ) {

        $this->response = $response;
        $this->boatRepository = $BoatRepository;
        $this->captainRepository = $captainRepository;
        $this->bookingRepository = $bookingRepository;
        $this->boatFavoriteRepository = $boatFavoriteRepository;
    }

    public function create(Request $request) {
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->createBoat($request->all())
        ]]);
    }

    public function updateBoatLocation(Request $request) {
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->updateBoatLocation($request->all())
        ]]);
    }

    public function addCaptain(Request $request) {
        return $this->response->respond(["data" => [
                        'boat' => $this->captainRepository->addCaptain($request->all())
        ]]);
    }

    public function deleteCaptain(Request $request) {
        $this->captainRepository->deleteCaptain($request->all());
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->getBoatDetail($request->all())
        ]]);
    }

    public function filterBoats(Request $request) {
        return $this->response->respond(["data" => [
                        'boats' => $this->boatRepository->filterBoats($request->all())
        ]]);
    }

    public function addBoatPrice(Request $request) {
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->addBoatPrice($request->all())
        ]]);
    }

    public function getBoatDetail(Request $request) {
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->getBoatDetail($request->all())
        ]]);
    }

    public function getBoatsWithType(Request $request) {
        $systemTax = (new SystemSettingRepository())->getSystemSettingActive();
        return $this->response->respond(["data" => [
            'boats' => $this->boatRepository->getBoatsWithType($request->all()),
            'tax' => $systemTax ? $systemTax['vat'] : null
        ]]);
    }

    public function getBoatCalender(Request $request) {
        $response = $this->boatRepository->getBoatCalender($request->all());
        return $this->response->respond(["data" => [
                        'schedule' => $response['schedules'],
                        'bookings' => $response['bookings']
                    ]
        ]);
    }

    public function getBoatDashboard(Request $request) {

        $boat = $this->boatRepository->getBoatDashboard($request->all());
        $customer_count = Arr::pull($boat, 'customers_count');
        $earning_count = Arr::pull($boat, 'earning_count');
        $booking_count = Arr::pull($boat, 'bookings_count');
        return $this->response->respond(["data" => [
                        'boat' => $boat,
                        'booking_count' => $booking_count,
                        'earning_count' => $earning_count,
                        'customer_count' => $customer_count
        ]]);
    }

    public function getCalendarBookings(Request $request) {
        $boat_id = Boat::where('boat_uuid', $request->get('boat_uuid'))->value('id');
        $user_id = User::where('user_uuid', $request->get('user_uuid'))->value('id');
        return $this->response->respond(["data" => [
                        'bookings' => $this->bookingRepository->getBoatBookingsByDate($boat_id, $request->get('date')),
        ]]);
    }

    public function getBoatBookings(Request $request) {
        $boat_id = Boat::where('boat_uuid', $request->get('boat_uuid'))->value('id');
        return $this->response->respond(["data" => [
                        'bookings' => $this->bookingRepository->getBookings('boat_id', $boat_id, $request->all()),
//            'boats' => $this->boatRepository->getBoatsWithType($request->all())
        ]]);
    }

    public function getBoatSettings(Request $request) {
        return $this->response->respond(["data" => [
                        'settings' => $this->boatRepository->getBoatSettings($request->all())
        ]]);
    }

    public function searchMapBoats(Request $request) {
        return $this->response->respond(["data" => [
                        'boats' => $this->boatRepository->searchMapBoats($request->all())
        ]]);
    }

    public function bookBoat(Request $request) {
        DB::beginTransaction();
        $booking = $this->bookingRepository->bookBoat($request->all());
        if ((isset($booking['success'])) && (($booking['success'] == false))) {
            return $this->response->respondCustomError($booking['message']);
        }
        return $this->response->respond(["data" => [
                        'booking' => $booking
        ]]);
    }

    public function updateCaptain(Request $request) {
        $this->captainRepository->updateCaptain($request->except('boat_uuid'));
        return $this->response->respond(["data" => [
                        'boat' => $this->boatRepository->getBoatDetail($request->all())
        ]]);
    }

    public function getFavouriteBoat(Request $request) {
        return $this->response->respond(["data" => [
                        'boats' => $this->boatFavoriteRepository->getUserFavouriteBoats($request->all())
        ]]);
    }

    public function removeBoat(Request $request) {
        $this->boatRepository->removeBoat($request->all());
        return $this->response->respond([]);
    }

}
