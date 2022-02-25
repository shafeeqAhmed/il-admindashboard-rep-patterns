<?php

namespace App\Repositories;

use App\Events\BookingCreatedEvent;
use App\Events\BookingStatusChange;
use App\Models\Boat;
use App\Models\Booking;
use App\Models\BookingTransaction;
use App\Models\User;
use App\Models\UserCard;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\CommonHelper;
use App\Traits\Responses\BookingResponse;
use Carbon\Carbon;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Str;
use DB;

/**
 * Class BookingRepository.
 */
class BookingRepository extends BaseRepository implements RepositoryInterface
{

    use BookingResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Booking::class;
    }

    public function getBoatTotalTours($boatId)
    {
        return $this->model->getBoatTotalTours($boatId);
    }

    public function getBookingDetail($id)
    {
        return $this->bookingResponse($this->model->getDetailsByCol('booking_uuid', $id));
    }

    public function boatBookingsByDate($boat_id, $params, $user_id)
    {

        return $this->mapMulitpleResponse($this->model->boatBookingsByDate($boat_id, $params, $user_id));
    }

    public function getBoatBookingsByDate($boat_id, $date)
    {
        return $this->mapMulitpleResponse($this->filterBoatBookingsCalender($this->model->getBoatBookingsByDate($boat_id, $date), Carbon::createFromDate($date)));
    }

    public function boatBookingsByStartDateEndDate($params)
    {
        return $this->model->boatBookingsByStartDateEndDate($params);
    }
    public function getMultipleSchedulesBookings($params) {
        return $this->model->getMultipleSchedulesBookings($params);
    }

    public function filterBoatBookingsCalender($bookings, $date)
    {
        $filtered_data = [];
        foreach ($bookings as $booking) {
            $start_date = Carbon::createFromTimestamp($booking['start_date_time']);
            $end_date = Carbon::createFromTimestamp($booking['end_date_time']);
            if ($date->between($start_date, $end_date)) {
                $filtered_data[] = $booking;
            } else {
                if ($date->toDateString() == $start_date->toDateString() || $date->toDateString() == $end_date->toDateString()) {
                    $filtered_data[] = $booking;
                }
            }
        }

        return $filtered_data;
    }

    public function mapMulitpleResponse($records)
    {
        $final = [];
        foreach ($records as $record) {

            $final[] = $this->bookingResponse($record);
        }
        return $final;
    }

    public function getBookingById($bookingUuid)
    {
        return $this->model->getBookingsById('booking_uuid', $bookingUuid)->toArray();
    }

    public function getBookings($col, $val, $params)
    {
        return $this->mapMulitpleResponse($this->model->getBookings($col, $val, $params));
    }

    public function getCustomerBookings($col, $val, $params)
    {
        return $this->mapMulitpleResponse($this->model->getCustomerBookings($col, $val, $params));
    }
    public function getAdminBookings($status)
    {
        return $this->model->getAdminBookings($status);
    }
    public function getOwnerBoatBookings($user, $type)
    {
        $user_obj = User::getOwnerBoatBookings($user, $type);
        $params['bookings'] = $this->prepareBookingResposeFromOwnerBoats($user_obj, $type);
        $params['user'] = $user_obj;
        return $params;
    }
    public function prepareBookingResposeFromOwnerBoats($user, $type = "all")
    {
        $response = [];
        $common_helper = new CommonHelper();
        if (!empty($user->boats)) {
            foreach ($user->boats as $index => $boats) {
                if (!empty($boats->bookings)) {
                    foreach ($boats->bookings as $key => $booking) {
                        $data['end_date_time'] = date('d-m-Y h:i a', $booking->end_date_time);
                        if ($type == "pending" && $common_helper->checkDateHours($booking['end_date_time'], 24)  === false){
                            continue;
                        }
                        $data = $booking->toArray();
                        $data['created_at'] = date('d-m-Y h:i a', strtotime($booking->created_at));
                        $data['created_at_converted'] = $this->convertDateTimeToLocalTimezone($booking->created_at, 'UTC', 'Asia/Riyadh');
                        $data['start_date_time'] = date('d-m-Y h:i a', $booking->start_date_time);
                        $data['start_date_time_converted'] = $this->convertDateTimeToLocalTimezone($data['start_date_time'], $booking->local_timezone, 'Asia/Riyadh');
                        $data['end_date_time'] = date('d-m-Y h:i a', $booking->end_date_time);
                        $data['end_date_time_converted'] = $this->convertDateTimeToLocalTimezone($data['end_date_time'], $booking->local_timezone, 'Asia/Riyadh');
                        $data['refund_amount'] = $booking->is_refund == 1 ? (!empty($booking->refundedTransaction) ? $booking->refundedTransaction->boat_earning : 0) : 0;
                        $data['owner']['boat_owner'] = $user->first_name . ' ' . $user->last_name;
                        $data['owner']['email'] = $user->email;
                        $response[] = $data;
                    }
                }
            }
        }
        return $response;
    }
    public function bookBoat($params)
    {
        //check boat
        $gatewayResponse = [];
        $checkConditions = $this->checBookingConditions($params);
        if ($checkConditions['success'] == false) {
            return ['success' => false, 'message' => $checkConditions['message']];
        }
        $booking = $this->getBookingById($this->create($this->mapOnTable($params))->booking_uuid);
        if (empty($booking)) {
            return ['success' => false, 'message' => 'Error Occurred while creating booking'];
        }
        $saveTransaction = $this->addBookingTransaction($booking, 'pending', $gatewayResponse, $params);
        if ($saveTransaction['success'] == false) {
            DB::rollback();
            return ['success' => false, 'message' => $saveTransaction['message']];
        }
//        event(new BookingCreatedEvent($booking));
        DB::commit();
        return $this->bookingResponse($booking);
    }

    public function checBookingConditions($params)
    {
        $boat = Boat::getBoatId('boat_uuid', $params['boat_uuid']);
        if (empty($boat)) {
            return ['success' => false, 'message' => 'Boat has been deleted'];
        }
        $params['boat_id'] = $boat['id'];
        //check boat bookings at selected time
        $user = User::getUserByUUId($params['user_uuid']);
        if (empty($user)) {
            return ['success' => false, 'message' => 'User does not exist'];
        }
        $params['user_id'] = $user['id'];
        $checkSchedule = $this->checkBookings($params);
        if (($checkSchedule['success'] == false)) {
            return ['success' => false, 'message' => $checkSchedule['message']];
        }
        //        $schedule_check = $this->checkBoatSchedule($appointment_array);
        // check customer schedule
        //        $checkUserSchedule = $this->checkCustomerBookings($params);
        //        if (($checkUserSchedule['success'] == false)) {
        //            return ['success' => false, 'message' => $checkUserSchedule['message']];
        //        }
        return ['success' => true, 'message' => 'Slot is available'];
    }

    public function checkBookings($params)
    {
        $bookings = Booking::checkBookingsAgainstTime($params['boat_id'], $params['user_id']);
        $params['start_date_time'] = strtotime($params['start_date_time']);
        $params['end_date_time'] = strtotime($params['end_date_time']);
        if (!empty($bookings)) {
            foreach ($bookings as $key => $booking) {

                if ($params['start_date_time'] >= $booking['start_date_time'] && $params['start_date_time'] < $booking['end_date_time']) {
                    return ['success' => false, 'message' => 'Already booked. Please select a different time'];
                }
                if (($params['end_date_time'] > $booking['start_date_time']) && ($params['end_date_time'] < $booking['end_date_time'])) {
                    return ['success' => false, 'message' => 'Already booked. Please select a different time'];
                }
            }
        }
        return ['success' => true, 'message' => 'Slot is available'];
    }

    public function checkCustomerBookings($params)
    {
        $user = User::getUserByUUId($params['user_uuid']);
        if (empty($user)) {
            return ['success' => false, 'message' => 'User does not exist'];
        }
        $bookings = Booking::checkBookingsAgainstTime('user_id', $user['id'], $params);
        if (!empty($bookings)) {
            return ['success' => false, 'message' => 'You already have a booking for the provided time. Please select a different time'];
        }
        return ['success' => true, 'message' => 'Slot is available'];
    }

    public function getCustomerBookingCount($user_id)
    {

        return $this->model->getCustomerBookingCount($user_id);
    }

    public function updateBooking($data)
    {
        $booking = $this->model->getDetailsByCol('booking_uuid', $data['booking_uuid']);
        $checkConditions = $this->checkConditionAndReturnParams($data, $booking);
        if ($checkConditions['success'] == false) {
            return $checkConditions;
        }
        if ($data['status'] == "confirmed") {
            // capture payment from customers card
            $paymentData = $this->checkAndConfirmBooking($data, $booking);
        }
        if (($booking['status'] != "confirmed") && ($data['status'] == "cancelled") || ($data['status'] == 'rejected')) {
            // unauthorize request since request it has been cancelled before confirm status
            $paymentData = $this->prepareUnAuthorizeRequest($booking, $data);
        }
        if ($booking['status'] == "confirmed" && $data['status'] == "cancelled" && $data['login_user_type'] == 'boat') {
            // customer will be refunded fully
            $paymentData = $this->prepareRefundCustomerCall($booking, $data);
        }
        if ($booking['status'] == "confirmed" && $data['status'] == "cancelled" && $data['login_user_type'] == 'customer') {
            /*  after deduction of boatek charges and other fees
              transfer remaining amount in boat earnings
              customer cancels before 7 days will get 70% refund of the paid amount
              between 3 - 7 days then 50%
              less than 3 days then the customer will not get an amount refund
             */
            $paymentData = $this->prepareDataToRefund($booking, $data);
        }
        if ($paymentData['success'] != true) {
            DB::rollback();
            return $paymentData;
        }
        DB::commit();
        // get updated booking detail
        $updatedBooking = $this->model->getDetailsByCol('booking_uuid', $data['booking_uuid']);
        return $this->bookingResponse($updatedBooking);
    }

    public function updateStatus($data)
    {
        $booking = $this->model->getDetailsByCol('booking_uuid', $data['booking_uuid']);
        $checkConditions = $this->checkConditionAndReturnParams($data, $booking);
        if ($checkConditions['success'] == false) {
            return $checkConditions;
        }
        $updateBooking = $this->updateBookingStatus($booking, ['status' => $data['status']]);
        if ($updateBooking['success'] == false) {
            return $updateBooking;
        }
        DB::commit();
        // get updated booking detail

        $updatedBooking = $this->model->getDetailsByCol('booking_uuid', $data['booking_uuid']);

        // booking email on status change and order cancelled whether he turned on notification for it or not.
        if((($updatedBooking['boat']['user']['notification_settings']['is_email_on_booking_cancelled'] ?? false) && $data['status'] == 'cancelled')
            || $data['status'] != 'cancelled')
        {
            $boater = $updatedBooking['boat']['user'];
            CommonHelper::sendEmail($boater, $updatedBooking, 'Booking status change', 'book_status_change');
        }
        $updatedBooking['login_user_type'] = $data['login_user_type'];
        event(new BookingStatusChange($updatedBooking));
        return $this->bookingResponse($updatedBooking);
    }

    public function rescheduleBooking($params)
    {
        $user_id = User::where('user_uuid', $params['user_uuid'])->value('id');
        $userBookings = $this->model->getUserUpcomingBookings($user_id);
        if ($userBookings) {
            $startTime = Carbon::parse($params['date'] . ' ' . $params['from_time']);
            //            $endTime = Carbon::parse($params['date'] . ' ' . $params['to_time']);

            foreach ($userBookings as $booking) {
                $start_date = Carbon::createFromTimestamp($booking->start_date_time);
                $end_date = Carbon::createFromTimestamp($booking->end_date_time);
                if ($startTime->between($start_date, $end_date)) {
                    return 'slot is reserved';
                }
            }
            $this->updateBookingStatus($params, ['start_date_time' => $startTime->timestamp]);
        }

        // update booking time
        return $this->bookingResponse($this->model->getDetailsByCol('booking_uuid', $params['booking_uuid']));
    }

    public function checkAndConfirmBooking($data, $booking)
    {
        $capturePayment['response'] = false;
        // get booking authorization data
        $authorizationData = \App\Models\BookingAuthorizationData::getBookingAuthData('merchant_reference', $booking['booking_uuid']);
        // if payment through MADA card then no need to capture or unauthorize just give capture status success and move on
        $paymentStatus = ((!empty($authorizationData)) && ($authorizationData['card_name']) == 'MADA') ? '04' : '00';
        // capture payment scenario
        if ((!empty($authorizationData)) && ($authorizationData['card_name'] != 'MADA') && ($authorizationData['digital_wallet'] != 'APPLE_PAY')) {
            $capturePayment = $this->capturePayment($authorizationData);
            $paymentStatus = isset($capturePayment->status) ? $capturePayment->status : '00';
        }
        if ((!empty($authorizationData)) && ($authorizationData['card_name'] != 'MADA') && ($authorizationData['digital_wallet'] == 'APPLE_PAY')) {
            $capturePayment = $this->captureApplePayment($authorizationData);
            $paymentStatus = isset($capturePayment->status) ? $capturePayment->status : '00';
        }
        if ((empty($authorizationData)) || ($paymentStatus != '04')) {
            // if payment not successful cancel booking status, add transaction and unauthoize payment request
            $unAuthorizeRequest = $this->prepareUnAuthorizeRequest($booking, $data);
            if (($unAuthorizeRequest['success'] == false)) {
                return $unAuthorizeRequest;
            }
            return ['success' => false, 'message' => __('booking_error')];
        }
        $saveTransaction = $this->addBookingTransaction($booking, 'confirmed', $capturePayment, $data);
        $updateBooking = $this->updateBookingStatus($booking, ['status' => 'confirmed']);
        if (($saveTransaction['success'] == false) || ($updateBooking['success'] == false)) {
            DB::rollback();
            return ['success' => false, 'message' => 'Error occurred while updating confirm status'];
        }
        return ['success' => true, 'message' => __('request_success')];
    }

    public function checkConditionAndReturnParams($data, $booking)
    {
        if (empty($booking)) {
            return ['success' => false, 'message' => __('booking_not_exists')];
        }

        if ($booking['start_date_time'] < strtotime(date('Y-m-d'))) {
            return ['success' => false, 'message' => __('booking_time_passed')];
        }
        return ['success' => true, 'data' => $data];
    }

    public function mapOnTable($param)
    {
        $settings = \App\Models\SystemSettings::where('is_active', 1)->first();
        $systemSettings = !empty($settings) ? $settings->toArray() : [];
        return [
            'booking_uuid' => Str::uuid()->toString(),
            'booking_short_id' => uniqid(),
            'start_date_time' => strtotime($param['start_date_time']),
            'end_date_time' => strtotime($param['end_date_time']),
            'boat_id' => Boat::where('boat_uuid', $param['boat_uuid'])->value('id'),
            'user_id' => User::where('user_uuid', $param['user_uuid'])->value('id'),
            'booking_price' => $param['booking_price'],
            // will be updated after payment
            'card_id' => null,
            'status' => 'pending_payment',
            'local_timezone' => $param['local_timezone'],
            'saved_timezone' => $param['saved_timezone'],
            'payment_received' => $param['payment_received'],
            'boatek_fee' => !empty($systemSettings['boatek_commission_charges']) ? $systemSettings['boatek_commission_charges'] : 0,
            'transaction_charges' => !empty($systemSettings['transaction_charges']) ? $systemSettings['transaction_charges'] : 0,
            'tax' => !empty($param['vat']) ? $param['vat'] : 0,
            'discount' => !empty($param['discount']) ? $param['discount'] : 0,
            'discount_type' => !empty($param['discount_type']) ? $param['discount_type'] : null,
        ];
    }

    public function capturePayment($inputs)
    {
        // payfort
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $url = config('general.payfort.payfort_url');
        $requestParams = array(
            'command' => 'CAPTURE',
            'access_code' => config('general.payfort.access_code'),
            'merchant_identifier' => config('general.payfort.merchant_identifier'),
            'merchant_reference' => $inputs['merchant_reference'],
            'amount' => $inputs['amount'],
            'currency' => 'SAR',
            'language' => 'en',
            'fort_id' => $inputs['fort_id'],
            //            'order_description' => 'iPhone 6-S',
        );
        $signature = $this->prepareSignature($requestParams);
        $requestParams['signature'] = $signature;
        $decoded_response = $this->prepareCurlRequest($url, $requestParams);
        return $decoded_response;
    }

    public function captureApplePayment($inputs)
    {
        // payfort
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $url = config('general.payfort.apple_payfort_url');
        $requestParams = array(
            'command' => 'CAPTURE',
            'access_code' => config('general.payfort.apple_access_code'),
            'merchant_identifier' => config('general.payfort.merchant_identifier'),
            'merchant_reference' => $inputs['merchant_reference'],
            'amount' => $inputs['amount'],
            'currency' => 'SAR',
            'language' => 'en',
            'fort_id' => $inputs['fort_id'],
            //            'order_description' => 'iPhone 6-S',
        );
        $signature = $this->prepareAppleSignature($requestParams);
        $requestParams['signature'] = $signature;
        $decoded_response = $this->prepareCurlRequest($url, $requestParams);
        return $decoded_response;
    }

    public function prepareSignature($requestParams)
    {
        $shaString = '';
        ksort($requestParams);
        foreach ($requestParams as $key => $value) {
            $shaString .= $key . '=' . $value;
        }
        $shaString = config("general.payfort.SHA_REQUEST_PHRASE") . $shaString . config("general.payfort.SHA_REQUEST_PHRASE");
        $signature = hash("sha256", $shaString);
        return $signature;
    }

    public function prepareAppleSignature($requestParams)
    {
        $shaString = '';
        ksort($requestParams);
        foreach ($requestParams as $key => $value) {
            $shaString .= $key . '=' . $value;
        }
        $shaString = config("general.payfort.APPLE_SHA_REQUEST_PHRASE") . $shaString . config("general.payfort.APPLE_SHA_REQUEST_PHRASE");
        $signature = hash("sha256", $shaString);
        return $signature;
    }

    public function prepareCurlRequest($url, $requestParams)
    {
        $ch = curl_init($url);
        $data = json_encode($requestParams);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $decodedData = json_decode($result);
        return $decodedData;
    }

    public function addBookingTransaction($booking, $status, $gatewayResponse = [], $data = [])
    {
        $transactionData = $this->prepareTransactionData($booking, $gatewayResponse, $data, $status);
        $saveData = \App\Models\BookingTransaction::createBookingTransaction($transactionData);
        if (!$saveData) {
            DB::rollback();
            return ['success' => false, 'message' => 'Error occurred while saving transaction status'];
        }
        return ['success' => true, 'message' => __('request_success')];
    }

    public function updateBookingStatus($booking, $updateData)
    {
        $updateBooking = Booking::updateBookingStatus('booking_uuid', $booking['booking_uuid'], $updateData);
        if (!$updateBooking) {
            DB::rollback();
            return ['success' => false, 'message' => __('request_failed')];
        }
        return ['success' => true, 'message' => __('request_success')];
    }

    public function prepareUnAuthorizeRequest($booking, $data)
    {
        $authorizationData = \App\Models\BookingAuthorizationData::getBookingAuthData('merchant_reference', $booking['booking_uuid']);
        if ((!empty($authorizationData)) && ($authorizationData['card_name'] == 'MADA')) {
            // process refund
            if ($data['login_user_type'] == "boat") {
                $paymentData = $this->prepareRefundCustomerCall($booking, $data);
            } elseif ($data['login_user_type'] == "customer") {
                $paymentData = $this->prepareDataToRefund($booking, $data);
            }
            if ($paymentData['success'] == false) {
                return ['success' => false, 'message' => $paymentData['message']];
            }
        } elseif ((!empty($authorizationData)) && ($authorizationData['card_name'] != 'MADA') && ($authorizationData['digital_wallet'] != 'APPLE_PAY')) {
            $unAuthorize = $this->unAuthorizeRequest($booking);
            if (($unAuthorize->status != '08')) {
                return ['success' => false, 'message' => 'Error occurred while  unauthorizing this request'];
            }
            $saveTransaction = $this->addBookingTransaction($booking, 'cancelled', $unAuthorize, $data);
            if ($saveTransaction['success'] == false) {
                return $saveTransaction;
            }
            $updateBooking = $this->updateBookingStatus($booking, ['status' => 'cancelled']);
            if ($updateBooking['success'] == false) {
                return $updateBooking;
            }
            return ['success' => true, 'message' => 'Successful Request'];
        } else {
            DB::rollback();
            return ['success' => false, 'message' => 'Error occurred while changing status'];
        }
    }

    public function unAuthorizeRequest($inputs)
    {
        $requestParams = array(
            'command' => 'VOID_AUTHORIZATION',
            'access_code' => config('general.payfort.access_code'),
            'merchant_identifier' => config('general.payfort.merchant_identifier'),
            'merchant_reference' => $inputs['booking_uuid'],
            'language' => 'en',
            // signature
        );
        $signature = $this->prepareSignature($requestParams);
        $requestParams['signature'] = $signature;
        $url = config("general.payfort.payfort_url");
        // production environment url
        $decodedData = $this->prepareCurlRequest($url, $requestParams);
        return $decodedData;
    }

    public function prepareTransactionData($booking = [], $gatewayResponse = [], $data = [], $status = 'pending')
    {
        return [
            'booking_transaction_uuid' => Str::uuid()->toString(),
            'booking_id' => $booking['id'],
            'price' => $booking['payment_received'],
            'gateway_response' => !empty($gatewayResponse) ? serialize($gatewayResponse) : null,
            'request_parameters' => !empty($data) ? serialize($data) : null,
            'transaction_status' => $status,
            'user_card_id' => !empty($booking['single_booking_transaction']['user_card_id']) ? $booking['single_booking_transaction']['user_card_id'] : null,
            'customer_refund' => !empty($booking['refund_amount']) ? $booking['refund_amount'] : 0,
            'boat_earning' => !empty($booking['boat_earning']) ? $booking['boat_earning'] : 0,
        ];
    }

    public function getBoatEarnedAmount($booking, $refundData = [])
    {
        $calculatedTransactionCharges = self::calculateBoatekCharges($booking['payment_received'], $booking['transaction_charges']);
        $amount_after_transaction_charges = $booking['payment_received'] - $calculatedTransactionCharges;
        $boatekCharges = self::calculateBoatekCharges($amount_after_transaction_charges, $booking['boatek_fee']);
        $amountAfterBoatekCharges = $amount_after_transaction_charges - $boatekCharges;
        $calculatedVat = self::calculateBoatekCharges($amountAfterBoatekCharges, $booking['tax']);
        $amount = $amountAfterBoatekCharges - $calculatedVat;
        if ((isset($refundData)) && (!empty($refundData)) && (!empty($refundData->amount))) {
            $amount = $amount - $refundData->amount;
        }
        return $amount;
    }

    public function calculateBoatekCharges($paid_amount, $fee)
    {

        $boatek_charges = ($paid_amount) * ($fee / 100);
        return !empty($boatek_charges) ? $boatek_charges : 0;
    }

    public function prepareRefundData($booking, $refundAmount = null)
    {
        $refundCall = [];
        if ($refundAmount > 0) {
            $booking['refund_amount'] = $refundAmount;
            $refundCall = $this->prepareRefundCall($booking);
            if ($refundCall->status != '06') {
                return ['success' => false, 'message' => __('request_failed'), 'response' => $refundCall];
            }
        }
        return ['success' => true, 'message' => 'Successful Request', 'response' => $refundCall];
    }

    public function getRefundAmount($booking, $refundType = null)
    {
        // TODO: boatek charges and payfort charges will be added from admin side
        $boatekCharges = !empty($booking['boatek_fee']) ? $booking['boatek_fee'] : 0;
        $payfortCharges = !empty($booking['transaction_charges']) ? $booking['transaction_charges'] : 0;
        $amount = null;
        if ($refundType == 1) {
            // full refund
            $amount = $booking['payment_received'];
        } elseif ($refundType == 2) {
            // 70% refund
            $amount = $booking['payment_received'] - (($booking['payment_received'] * 30) / 100);
        } elseif ($refundType == 3) {
            // 50% refund
            $amount = $booking['payment_received'] - (($booking['payment_received'] * 50) / 100);
        }
        $finalAmount = ((!empty($amount)) && ($amount != 0)) ? $amount - $boatekCharges - $payfortCharges : 0;
        return $finalAmount;
    }

    public function getRefundAndEarnedAmount($booking, $refundType = null)
    {
        // TODO: boatek charges and payfort charges will be added from admin side
        $boatekCharges = !empty($booking['boatek_fee']) ? $booking['boatek_fee'] : 0;
        $payfortCharges = !empty($booking['transaction_charges']) ? $booking['transaction_charges'] : 0;
        $amount_after_deduction = !empty($booking['payment_received']) ? ((($booking['payment_received'] - $boatekCharges) - $payfortCharges)) : 0;
        $amount['customer_refund'] = null;
        $amount['boat_earning'] = null;
        if ($refundType == 1) {
            // customer will receive full refund boat will get nothing
            $amount['customer_refund'] = $booking['payment_received'];
            $amount['boat_earning'] = 0;
        } elseif ($refundType == 2) {
            // 70% refund to customer and remaining to boat

            $amount['customer_refund'] = $amount_after_deduction - (($booking['payment_received'] * 30) / 100);
            $amount['boat_earning'] = $amount_after_deduction - $amount['customer_refund'];
        } elseif ($refundType == 3) {
            // 50% refund customer and remaining to boat
            $amount['customer_refund'] = $amount_after_deduction - (($booking['payment_received'] * 50) / 100);
            $amount['boat_earning'] = -$amount_after_deduction - $amount['customer_refund'];
        }
        return $amount;
    }

    public function calculateCustomerRefundAmount($data, $booking, $refundType = null)
    {
        $boatekCharges = !empty($booking['boatek_fee']) ? $booking['boatek_fee'] : 0;
        $payfortCharges = !empty($booking['transaction_charges']) ? $booking['transaction_charges'] : 0;
        if ($refundType == 1) {
            // customer refund is 0
            $amount = 0;
        } elseif ($refundType == 2) {
            // 70% earning to boat and remaining refund to customer
            $amount = $booking['payment_received'] - $data['boat_refund'];
        } elseif ($refundType == 3) {
            // 50% earning to boat and remaining refund to customer
            $amount = $booking['payment_received'] - $data['boat_refund'];
        }
        $finalAmount = ((!empty($amount)) && ($amount != 0)) ? $amount - $boatekCharges - $payfortCharges : 0;
        return $finalAmount;
    }

    public function prepareRefundCall($inputs)
    {
        /*
         * According to payfort documentation
         *  Before sending the transaction value you must multiply the value by a factor that matches the ISO 4217 specification for that currency. Multiplication is necessary to accommodate decimal values. Each currency’s 3-digit ISO code will have a specification for the number of digits after the decimal separator.
          For example: If the transaction value is 500 AED; according to ISO 4217, you should multiply the value with 100 (to accommodate 2 decimal points). You will therefore send an AED 500 purchase amount as a value of 50000.
         */
        $amount = $inputs['refund_amount'] * 100;
        $requestParams = array(
            'command' => 'REFUND',
            'access_code' => config('general.payfort.access_code'),
            'merchant_identifier' => config('general.payfort.merchant_identifier'),
            'merchant_reference' => $inputs['booking_uuid'],
            'amount' => $amount,
            'currency' => 'SAR',
            'language' => 'en',
        );
        $signature = self::prepareSignature($requestParams);
        $requestParams['signature'] = $signature;
        $url = config("general.payfort.payfort_url");
        // production environment url
        $decodedData = self::prepareCurlRequest($url, $requestParams);
        return $decodedData;
    }

    public function prepareRefundCustomerCall($booking, $data)
    {
        $refundAmount = $this->getRefundAmount($booking, 1);
        $refundPayment = $this->prepareRefundData($booking, $refundAmount);
        if ($refundPayment['success'] == false) {
            return $refundPayment;
        }
        $booking['refund_amount'] = $refundAmount;
        $cancelPaymentData = $this->addBookingTransaction($booking, 'refunded', $refundPayment['response'], $data);
        if ($cancelPaymentData['success'] == false) {
            return $cancelPaymentData;
        }
        $updateBooking = $this->updateBookingStatus($booking, ['status' => 'cancelled']);
        if ($updateBooking['success'] == false) {
            return $updateBooking;
        }
        return ['success' => true, 'message' => __('request_succsss')];
    }

    public function getRefundType($booking)
    {
        $refundType = null;
        $timeDifference = CommonHelper::getTimeDifferenceInMinutes(strtotime(date('Y-m-d H:i:s')), $booking['start_date_time']);
        if ($timeDifference > 10080) {
            // more then 7 days then customer will be refunded 70% and remaining to boat
            $refundType = 2;
        } elseif ($timeDifference < 10080 && $timeDifference > 4320) {
            // 10080 minutes in 7 days and 4320 minutes in 3 days
            // between 7 and 3 days then 50% refund to customer and remaining to boat
            $refundType = 3;
        } else {
            // no refund
            $refundType = 0;
        }
        return $refundType;
    }

    public function prepareDataToRefund($booking, $data)
    {
        $refundPayment = [];
        $refundType = $this->getRefundType($booking);
        // get boat earning and customer refund
        $preparedAmounts = $this->getRefundAndEarnedAmount($booking, $refundType);
        $booking['boat_earning'] = $preparedAmounts['boat_earning'];
        $booking['refund_amount'] = $preparedAmounts['customer_refund'];
        if ((!empty($preparedAmounts['customer_refund'])) && ($preparedAmounts['customer_refund'] > 0)) {
            $refundPayment = $this->prepareRefundCall($booking);
            if ($refundPayment->status != '06') {
                return ['success' => false, 'message' => 'Error occurred while refunding amount'];
            }
        }
        $saveTransaction = $this->addBookingTransaction($booking, 'refunded', $refundPayment, $data);
        if ($saveTransaction['success'] == false) {
            return $saveTransaction;
        }
        $updateBooking = $this->updateBookingStatus($booking, ['status' => 'cancelled']);
        if ($updateBooking['success'] == false) {
            return $updateBooking;
        }
        return ['success' => true, 'message' => __('request_success')];
    }
    public function paymentReceivedAble($boatIds, $start_date, $end_date)
    {
        $paymentReceivable =  $this->model->paymentReceivedAble($boatIds, $start_date, $end_date);
        $transactionAmount = $this->paymentTransactionBoatEarning($boatIds, $start_date, $end_date);
        return ($paymentReceivable + $transactionAmount);
    }
    public function paymentTransactionBoatEarning($boatIds, $start_date, $end_date)
    {
        $pendingRefundAbleBookingIds = $this->model->pendingRefundAbleBookingIds($boatIds, $start_date, $end_date);
        //       i will optimize it latter on
        return  BookingTransaction::whereIn('booking_id', $pendingRefundAbleBookingIds)
            ->where('transaction_status', 'refunded')
            ->sum('boat_earning');
    }
}
