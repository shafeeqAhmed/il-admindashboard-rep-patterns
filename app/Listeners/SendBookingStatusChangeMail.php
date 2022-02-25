<?php

namespace App\Listeners;

use App\Events\BookingStatusChange;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendBookingStatusChangeMail
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
        $boater = $booking['boat']['user'];
        CommonHelper::sendEmail($boater, $booking, 'Order has been confirmed!', 'book_status_change');
    }
}
