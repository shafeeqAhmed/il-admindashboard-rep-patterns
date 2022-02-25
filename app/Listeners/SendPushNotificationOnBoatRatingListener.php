<?php

namespace App\Listeners;

use App\Models\Boat;
use App\Models\User;
use App\Traits\ProcessNotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPushNotificationOnBoatRatingListener
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
    public function handle($review)
    {
        $review = ((array)$review)['booking'];
        $inputs['login_user_type'] = 'boat';
//        $inputs['boat_uuid'] = 'ef529f29-23b4-3ce6-af11-693f3378d401';
//        $inputs['user_uuid'] = 'e666a8c5-7179-3db5-b687-83ea4ff8d36b';
        $inputs['boat_uuid'] = Boat::where('id', $review['boat_id'])->value('boat_uuid');
        $inputs['user_uuid'] = User::where('id', $review['user_id'])->value('user_uuid');
        $inputs['review_id'] = $review['id'];

        ProcessNotificationHelper::sendRatingNotification($inputs, $review, 'new_rating');
    }
}
