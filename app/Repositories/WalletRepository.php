<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\WalletResponse;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Repositories\BoatRepository;
use App\Repositories\BookingRepository;
use Illuminate\Support\Str;
use DB;

class WalletRepository extends BaseRepository implements RepositoryInterface {

    use WalletResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return \App\Models\Booking::class;
    }

    public function getTransactions($params) {
        $status = ($params['status'] == 'pending') ? 'pending' : false;



        if($params['type'] == 'boat') {
            $boatRepository = new BoatRepository();
            $boatId = $boatRepository->getBoatIdByUuid($params['uuid']);
            if (empty($boatId)) {
                return ['success' => false, 'message' => 'Boat does not exist'];
            }
            $bookings = $this->model->getBookingsCalendar('boat_id', $boatId, $status);
        } else {
            $userRepository = new UserRepository();
            $user = $userRepository->getByColumn($params['uuid'],'user_uuid');
            if (empty($user)) {
                return ['success' => false, 'message' => 'Customer does not exist'];
            }
            $bookings = $this->model->getBookingsCalendar('user_id', $user->id, $status);
        }

        $response = $this->mapMulitpleResponse($bookings);
        return $response;
    }
    public function getPendingTransactions($params) {
        $boatRepository = new BoatRepository();
        $boatId = $boatRepository->getBoatIdByUuid($params['boat_uuid']);
        if (empty($boatId)) {
            return ['success' => false, 'message' => __('boat_not_exists')];
        }
        $bookings = $this->model->getBookings('boat_id', $boatId, 'pending');
        $bookingRepository = new BookingRepository();
        $response = $this->mapMulitpleResponse($bookings);
        return $response;
    }
    public function getTransactionDetail($params) {
        $boatId = (new BookingRepository())->getByColumn($params['transaction_uuid'],'booking_uuid');
        if (empty($boatId)) {
            return ['success' => false, 'message' => 'Transaction Does not exist'];
        }
        $booking = (new BookingRepository)->makeModel()->getDetailsByCol('booking_uuid',$params['transaction_uuid']);
        return $this->transactionDetailResponse($booking);
    }



    public function mapMulitpleResponse($records) {
        $final = [];
        foreach ($records as $record) {

            $final[] = $this->transactionResponse($record);
        }
        return $final;
    }


    public function getBalance($params) {


//        if($params['type'] == 'boat') {
//            $boatRepository = new BoatRepository();
//            $boatId = $boatRepository->getBoatIdByUuid($params['uuid']);
//            if (empty($boatId)) {
//                return ['success' => false, 'message' => 'Boat does not exist'];
//            }
//            $bookings = $this->model->getBookingsCalendar('boat_id', $boatId, $status);
//        } else {
//            $userRepository = new UserRepository();
//            $user = $userRepository->getByColumn($params['uuid'],'user_uuid');
//            if (empty($user)) {
//                return ['success' => false, 'message' => 'Customer does not exist'];
//            }
//            $bookings = $this->model->getBookingsCalendar('user_id', $user->id, $status);
//        }

        if($params['type'] == 'boat') {
            $boatId = (new BoatRepository())->getBoatIdByUuid($params['uuid']);
            if (empty($boatId)) {
                return ['success' => false, 'message' => __('boat_not_exists')];
            }
            $balance['pending_balance'] = Booking::getBalance('boat_id', $boatId, ['pending', 'confirmed']);
            $balance['available_balance'] = Booking::getBalance('boat_id', $boatId, ['completed']);
        } else {
            $userRepository = new UserRepository();
            $user = $userRepository->getByColumn($params['uuid'],'user_uuid');
            if (empty($user)) {
                return ['success' => false, 'message' => 'Customer does not exist'];
            }

            $balance['pending_balance'] = Booking::getBalance('user_id', $user->id, ['pending', 'confirmed']);
            $balance['available_balance'] = Booking::getBalance('user_id', $user->id, ['completed']);
        }

        $response = $this->prepareBalanceResponse($balance);
        return $response;
    }

    public function addBankDetail($params) {
        $params['user_id'] = (new UserRepository())->getUser($params['user_uuid'])->id;
        if (empty($params['user_id'])) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $prepareParams = $this->mapOnTable($params);
        $saveData = \App\Models\BankDetail::updateOrCreateData('user_id', $prepareParams['user_id'], $prepareParams);
        if (!$saveData) {
            return ['success' => false, 'message' => __('request_failed')];
        }
        DB::commit();
        $saveData['user_uuid'] = $params['user_uuid'];
        $response = $this->prepareBankResponse($saveData);
        return $response;
    }

    public function getBankDetail($params) {
        $userId = (new UserRepository())->getUser($params['user_uuid'])->id;
        if (empty($userId)) {
            return ['success' => false, 'message' => __('invalid_user')];
        }
        $getData = \App\Models\BankDetail::getDetail('user_id', $userId);
        $getData['user_uuid'] = $params['user_uuid'];
        $response = $this->prepareBankResponse($getData);
        return $response;
    }

    public function mapOnTable($params) {
        return [
            'bank_detail_uuid' => Str::uuid()->toString(),
            'account_title' => !empty($params['account_title']) ? $params['account_title'] : null,
            'user_id' => !empty($params['user_id']) ? $params['user_id'] : null,
            'account_name' => !empty($params['account_name']) ? $params['account_name'] : null,
            'account_number' => !empty($params['account_number']) ? $params['account_number'] : null,
            'iban_account_number' => !empty($params['iban_account_number']) ? $params['iban_account_number'] : null,
            'bank_name' => !empty($params['bank_name']) ? $params['bank_name'] : null,
            'billing_address' => !empty($params['billing_address']) ? $params['billing_address'] : "",
            'post_code' => !empty($params['post_code']) ? $params['post_code'] : "",
            'location_type' => !empty($params['location_type']) ? $params['location_type'] : 'KSA',
        ];
    }

}
