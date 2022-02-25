<?php

namespace App\Listeners;


use App\Helpers\SendEmailOnBookingCreation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailOnBookingCreationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public $sendEmailOnBookingCreation = "";
    public function __construct(SendEmailOnBookingCreation $sendEmailOnBookingCreation)
    {
        $this->sendEmailOnBookingCreation = $sendEmailOnBookingCreation;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     */
    public function handle($event)
    {
       return $this->sendEmailOnBookingCreation->sendEmail($event->booking);
    }
}
