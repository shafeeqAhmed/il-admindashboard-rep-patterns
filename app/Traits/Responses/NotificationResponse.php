<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


use App\Repositories\BoatRepository;
use App\Repositories\BoatReviewRepository;
use App\Repositories\UserRepository;

trait NotificationResponse
{
    public function notificationResponse($notification){

        return [
            'id' => $notification['notification_uuid'],
            'message' => $notification['message'],
            'isRead'=>($notification['is_read'] ==0)?false:true,
            'notification_type'=>$notification['object_type'],
            'date'=>$this->convertDateToTimeZone($notification['created_at']),
            'time'=>$this->convertToTimeZone($notification['created_at']),
            'user'=>(isset($notification['user']))?(new UserRepository())->userResponse((object)$notification['user']):null,
            'boat'=>(isset($notification['boat']))?(new BoatRepository())->boatResponse($notification['boat']):null
        ];



    }

    public static function convertDateToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new \DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d'); // 2020-8-13
    }

    public static function convertToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {

        $date = new \DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('H:i'); // 2020-8-13
    }

    public static function convertStrToDateAndTime($date) {
        return date('Y-m-d H:i:s', $date);
    }
}
