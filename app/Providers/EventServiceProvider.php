<?php

namespace App\Providers;

use App\Events\BoatRatingEvent;
use App\Events\BookingCreatedEvent;
use App\Events\BookingRescheduleEvent;
use App\Events\BookingStatusChange;
use App\Events\PostLikeEvent;
use App\Listeners\BookingCreatedListener;
use App\Listeners\SendBookingStatusChangeMail;
use App\Listeners\SendEmailOnBookingCreationListener;
use App\Listeners\SendPushNotificationOnBoatRatingListener;
use App\Listeners\SendPushNotificationOnBookingCreationListener;
use App\Listeners\SendPushNotificationOnBookingRescheduleListener;
use App\Listeners\SendPushNotificationOnBookingStatusChangeListener;
use App\Listeners\SendPushNotificationOnPostLikeListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        BookingCreatedEvent::class => [
            BookingCreatedListener::class,
//            SendEmailOnBookingCreationListener::class,
            SendPushNotificationOnBookingCreationListener::class
        ],
        BookingStatusChange::class => [
//            SendBookingStatusChangeMail::class,
            SendPushNotificationOnBookingStatusChangeListener::class
        ],
        BoatRatingEvent::class => [
            SendPushNotificationOnBoatRatingListener::class
        ],
        PostLikeEvent::class => [
            SendPushNotificationOnPostLikeListener::class
        ],
        BookingRescheduleEvent::class => [
            SendPushNotificationOnBookingRescheduleListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
