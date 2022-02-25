<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


use App\Repositories\BoatServicesRepository;
use App\Repositories\CaptainRepository;

trait CaptainResponse
{
    public function captainResponse($boat){
       return [
            'captain_uuid'=>$boat['captain_uuid'],
            'first_name'=>$boat['captain_user']['first_name'],
            'last_name'=>$boat['captain_user']['last_name'],
            'email'=>$boat['captain_user']['email'],
            'image' => $boat['captain_user']['profile_pic'] ? generateThumbnailsResponse($boat['captain_user']['profile_pic'], 'mobile_uploads') : null
        ];
    }
}
