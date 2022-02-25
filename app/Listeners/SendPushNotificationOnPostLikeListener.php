<?php

namespace App\Listeners;

use App\Traits\ProcessNotificationHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\BoatPost;

class SendPushNotificationOnPostLikeListener
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
     * @return void
     */
    public function handle($postLike)
    {
        $postLike = ((array)$postLike)['post'];
        $post = BoatPost::where('id', $postLike['post_id'])->with('boat.user')->first()->toArray();
        $postLike['post_uuid'] = $post['post_uuid'];
        $receiver = $post['boat']['user'];
        $inputs['user_id'] = $receiver['id'];
        ProcessNotificationHelper::sendLikeNotification($inputs, $postLike, 'new_like');

    }
}
