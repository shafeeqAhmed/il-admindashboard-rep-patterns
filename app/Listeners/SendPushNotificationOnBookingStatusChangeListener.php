<?php

namespace App\Listeners;

use App\Models\BoatPost;
use App\Traits\ProcessNotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPushNotificationOnBookingStatusChangeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
//        $inputs['user_id'] = $booking['booking']['user']['id'];
//        $inputs['boat_id'] = $booking['booking']['boat']['user']['id'];
        ProcessNotificationHelper::sendAppointmentStatusNotification($inputs, $booking, 'change_appointment_status');
    }
}
