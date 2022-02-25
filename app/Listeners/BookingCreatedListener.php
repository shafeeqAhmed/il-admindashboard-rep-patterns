<?php

namespace App\Listeners;

use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BookingCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $notificationRepository = "";
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository= $notificationRepository;
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     */
    public function handle($event)
    {
        return $this->notificationRepository->sendBookingCreatedNotification($event->booking);
    }
}
