<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;

use App\Traits\CommonHelper;

trait UserResponse {

    public function userResponse($user) {

        return [
            'userUuid' => $user->user_uuid,
            'firstName' => $user->first_name,
            'lastName' => $user->last_name,
            'email' => $user->email,
            'countryCode' => $user->country_code,
//            'profile_pic ' => $this->profileImagesResponse($user->profile_pic),
            'profile_pic' => !empty($user->profile_pic)?generateThumbnailsResponse($user->profile_pic, 'mobile_uploads'):null,
            'countryName' => $user->country_name,
            'phoneNumber' => $user->phone_number,
            'role' => $user->role,
            'login_user_type' => (isset($user->login_user_type))? $user->login_user_type:null,
            'isVerified' => ($user->is_verified == 1) ? true : false
        ];
    }

}
