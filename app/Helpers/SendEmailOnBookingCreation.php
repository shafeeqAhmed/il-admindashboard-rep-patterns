<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 1/7/2022
 * Time: 1:53 PM
 */

namespace App\Helpers;
use App\Models\User;
use App\Traits\CommonHelper;
use Illuminate\Support\Facades\Mail;



class SendEmailOnBookingCreation
{


    public function sendEmail($booking){
        $user = $booking['user'];
        CommonHelper::sendEmail($user, $booking, 'New booking request!', 'book_created');
        return true;
    }
}
