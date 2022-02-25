<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait NotificationSettingResponse
{
    public function notificationSettingResponse($notificationSetting, $type)
    {
        if($type=='boat'){
            return $this->responseBoatOwner($notificationSetting);
        } else {
            return $this->responseCustomer($notificationSetting);
        }
    }

    public function responseBoatOwner($notificationSetting){
        return [
            'notification_settings_uuid' => $notificationSetting['notification_settings_uuid'],
            'is_boat_booked' => $notificationSetting['is_boat_blocked'],
            'is_payment_received' => $notificationSetting['is_payment_received'],
            'is_booking_cancelled' => $notificationSetting['is_booking_cancelled'],
            'is_email_on_boat_booked' => $notificationSetting['is_email_on_boat_blocked'],
            'is_email_on_booking_cancelled' => $notificationSetting['is_email_on_booking_cancelled'],
            'is_email_on_payment_received' => $notificationSetting['is_email_on_payment_received'],
        ];
    }

    public function responseCustomer($notificationSetting){
        return [
            'notification_settings_uuid' => $notificationSetting['notification_settings_uuid'],
            'is_confirmed_customer' => $notificationSetting['is_confirmed_customer'],
            'is_rescheduled_customer' => $notificationSetting['is_rescheduled_customer'],
            'is_cancelled_customer' => $notificationSetting['is_cancelled_customer'],
            'is_confirmed_email_customer' => $notificationSetting['is_confirmed_email_customer'],
            'is_rescheduled_email_customer' => $notificationSetting['is_rescheduled_email_customer'],
            'is_cancelled_email_customer' => $notificationSetting['is_cancelled_email_customer'],
        ];
    }
}
