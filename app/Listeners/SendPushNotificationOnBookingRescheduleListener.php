<?php

namespace App\Listeners;

use App\Events\BookingRescheduleEvent;
use App\Models\Boat;
use App\Models\User;
use App\Traits\ProcessNotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPushNotificationOnBookingRescheduleListener
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
     * @return void
     */
    public function handle($booking)
    {
        $booking = ((array)$booking)['booking'];
        $inputs['login_user_type'] = $booking['login_user_type'];
        $inputs['boat_uuid'] = Boat::where('id', $booking['boat_id'])->value('boat_uuid');
        $inputs['user_uuid'] = User::where('id', $booking['user_id'])->value('user_uuid');
        ProcessNotificationHelper::sendRescheduledAppointmentNotification($booking, $inputs, 'reschedule_appointment');
    }
}
