<?php

namespace App\Listeners;

use App\Traits\ProcessNotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Boat;
use App\Models\User;

class SendPushNotificationOnBookingCreationListener
{

    public $sendPushNotificationOnBooking = "";

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->sendPushNotificationOnBooking = $data;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($booking)
    {
        $booking = ((array)$booking)['booking'];
        $inputs['login_user_type'] = $booking['login_user_type'];
        $inputs['boat_uuid'] = Boat::where('id', $booking['boat_id'])->value('boat_uuid');
        $inputs['user_uuid'] = User::where('id', $booking['user_id'])->value('user_uuid');
        ProcessNotificationHelper::sendAppointmentNotification($booking, $inputs, 'new_appointment');
    }
}
