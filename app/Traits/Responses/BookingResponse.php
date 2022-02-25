<?php

namespace App\Traits\Responses;

use App\Models\Boat;
use App\Repositories\BoatRepository;
use App\Repositories\UserRepository;
use App\Traits\CommonHelper;
use Carbon\Carbon;
use DateTime;

trait BookingResponse {

    public function bookingResponse($booking) {
        $boatObj = new BoatRepository();

        return [
            "id" => $booking['booking_uuid'],
            "bookingShort" => $booking['booking_short_id'],
            "boat_uuid" => (isset($booking['boat'])) ? $booking['boat']['boat_uuid'] : null,
//            "strtdate" => $this->convertDateToTimeZone($booking['start_date_time'], 'UTC', $booking['local_timezone']),
//            "enddate" => $this->convertDateToTimeZone($booking['end_date_time'], 'UTC', $booking['local_timezone']),
//            'startTime' => $this->convertToTimeZone($booking['start_date_time'], 'UTC', $booking['local_timezone']),
//            'endTime' => $this->convertToTimeZone($booking['end_date_time'], 'UTC', $booking['local_timezone']),
            "strtdate" => date('Y-m-d', $booking['start_date_time']),
            "enddate" => date('Y-m-d', $booking['end_date_time']),
            'startTime' => date('H:i:s', $booking['start_date_time']),
            'endTime' => date('H:i:s', $booking['end_date_time']),
            'totalHours' => CommonHelper::getTimeDifferenceInHours($booking['start_date_time'], $booking['end_date_time']),
            "status" => $booking['status'],
            "savedTz" => $booking['saved_timezone'],
            "localTz" => $booking['local_timezone'],
            "notes" => $booking['notes'],
            "bookPrice" => $booking['booking_price'],
            "totalPaidAmount" => $booking['payment_received'],
            "cardId" => $booking['card_id'],
            "boatekFee" => $booking['boatek_fee'],
            "bookingPrice" => $booking['booking_price'],
            "paymentReceived" => $booking['payment_received'],
            "transCharges" => $booking['transaction_charges'],
            "tax" => $booking['tax'],
            "discount" => $booking['discount'],
            "discountType" => $booking['discount_type'],
            "promoId" => $booking['promo_code_id'],
            "isTransferred" => $booking['is_transferred'],
            "isDisputed" => $booking['is_disputed'],
            "reviews" => isset($booking['reviews']) ? $booking['reviews'] : null,
            'boat' => (isset($booking['boat'])) ? $boatObj->getBoatDetail($booking['boat']) : null,
            'customer' => (new UserRepository())->getUserById($booking['user_id'])
        ];
    }

    public static function convertDateToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = self::convertStrToDateAndTime($date);
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d'); // 2020-8-13
    }

    public static function convertToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = self::convertStrToDateAndTime($date);
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('H:i'); // 2020-8-13
    }

    public static function convertStrToDateAndTime($date) {
        return date('Y-m-d H:i:s', $date);
    }

    public static function convertDateTimeToLocalTimezone($date, $default_timezone, $local_timezone) {
        return Carbon::parse(date('d-m-Y h:i a', strtotime($date))  .' '. $default_timezone)->tz($local_timezone)->format('d-m-Y h:i a');
    }

}
