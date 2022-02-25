<?php

namespace App\Traits\Responses;

use App\Repositories\BoatRepository;
use App\Repositories\UserRepository;
use App\Traits\CommonHelper;

trait WalletResponse {

    public function prepareBalanceResponse($balance = []) {
        $response = [];
        if (!empty($balance)) {
            $response['available_balance'] = !empty($balance['available_balance']) ? $balance['available_balance'] : 0;
            $response['pending_balance'] = !empty($balance['pending_balance']) ? $balance['pending_balance'] : 0;
        }
        return $response;
    }
    public function transactionResponse($booking){
        return [
            "transaction_uuid" => $booking['booking_uuid'],
            "bookingShort" => $booking['booking_short_id'],
            'boat_name' => (isset($booking['boat'])) ? $booking['boat']['name'] : null,
            'boat_number' => (isset($booking['boat'])) ? $booking['boat']['number'] : null,
            "strtdate" => date('Y-m-d', $booking['start_date_time']),
            "enddate" => date('Y-m-d', $booking['end_date_time']),
            'startTime' => date('H:i:s', $booking['start_date_time']),
            'endTime' => date('H:i:s', $booking['end_date_time']),
            'totalHours' => CommonHelper::getTimeDifferenceInHours($booking['start_date_time'], $booking['end_date_time']),
            "status" => $booking['status'],
            "bookPrice" => $booking['booking_price'],
        ];
    }
    public function transactionDetailResponse($booking){
        return [
            "transaction_uuid" => $booking['booking_uuid'],
            "bookingShort" => $booking['booking_short_id'],
            'boat_name' => (isset($booking['boat'])) ? $booking['boat']['name'] : null,
            'boat_number' => (isset($booking['boat'])) ? $booking['boat']['number'] : null,
            'boat_default_pic' => (isset($booking['boat'])) ? $booking['boat']['profile_pic'] : null,
            'boat_price' => (isset($booking['boat'])) ? $booking['boat']['price'] : null,
            'price_unit' => (isset($booking['boat'])) ? $booking['boat']['price_unit'] : null,
            "strtdate" => date('Y-m-d', $booking['start_date_time']),
            "enddate" => date('Y-m-d', $booking['end_date_time']),
            'startTime' => date('H:i:s', $booking['start_date_time']),
            'endTime' => date('H:i:s', $booking['end_date_time']),
            'totalHours' => CommonHelper::getTimeDifferenceInHours($booking['start_date_time'], $booking['end_date_time']),
            "totalPaidAmount" => $booking['payment_received'],
            "totalAmount" => $booking['booking_price'],
            'cardId' => (isset($booking['card'])) ? $booking['card']['card_id'] : null,
            'promoId' => (isset($booking['promo_code'])) ? $booking['promo_code']['coupon_code'] : null,
            "discount" => $booking['discount'],
            "discountType" => $booking['discount_type'],
            "status" => $booking['status'],
            "customer" => (new UserRepository())->userResponse((object)$booking['user']),
            "earnedAmount" => $this->calculateBookingAmount($booking)
        ];
    }

    public function prepareBankResponse($params = []) {
        $response = [];
        if (!empty($params)) {
            $response = [
                'account_title' => !empty($params['account_title']) ? $params['account_title'] : null,
                'user_uuid' => $params['user_uuid'],
                'account_name' => !empty($params['account_name']) ? $params['account_name'] : null,
                'account_number' => !empty($params['account_number']) ? $params['account_number'] : null,
                'iban_account_number' => !empty($params['iban_account_number']) ? $params['iban_account_number'] : null,
                'bank_name' => !empty($params['bank_name']) ? $params['bank_name'] : null,
                'billing_address' => !empty($params['billing_address']) ? $params['billing_address'] : "",
                'post_code' => !empty($params['post_code']) ? $params['post_code'] : "",
                'location_type' => !empty($params['location_type']) ? $params['location_type'] : 'KSA',
            ];
        }
        return $response;
    }

    public function calculateBookingAmount($book){
        $booking_amount = $book['payment_received'] - (($book['boatek_fee'] ?? 0) + ($book['transaction_charges'] ?? 0));
        if ($book['is_refund']){
            $booking_amount = $book['refunded_transaction']['boat_earning'] ?? 0;
        }
        return $booking_amount;
    }

}
