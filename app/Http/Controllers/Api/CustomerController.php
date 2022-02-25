<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Models\User;
use App\Repositories\BoatFavoriteRepository;
use App\Repositories\BookingRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\FavouriteBoatsRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    protected $response = "";
    protected $customerRepository = "";
    protected $bookingRepository = "";
    protected $favouriteBoatsRepository = "";

    public function __construct(
            ApiResponse $response,
            CustomerRepository $CustomerRepository,
            BoatFavoriteRepository $boatFavoriteRepository,
            BookingRepository $bookingRepository
    ) {
        $this->response = $response;
        $this->customerRepository = $CustomerRepository;
        $this->bookingRepository = $bookingRepository;
        $this->favouriteBoatsRepository = $boatFavoriteRepository;
    }

    public function getCustomerDashboardCount(Request $request) {
        $user_id = User::where('user_uuid', $request->get('customer_uuid'))->value('id');
        return $this->response->respond(["data" => [
                        'totalBooking' => $this->bookingRepository->getCustomerBookingCount($user_id),
                        'favouriteBoat' => $this->favouriteBoatsRepository->getFavouriteBoatsByUser($user_id),
        ]]);
    }

    public function getCustomerBooking(Request $request) {
        $user_id = User::where('user_uuid', $request->get('customer_uuid'))->value('id');
        return $this->response->respond(["data" => [
                        'bookings' => $this->bookingRepository->getCustomerBookings('user_id', $user_id, $request->all()),
        ]]);
    }

}
