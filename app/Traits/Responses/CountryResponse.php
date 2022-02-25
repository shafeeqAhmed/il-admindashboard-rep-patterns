<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;

use App\Repositories\UserRepository;
use App\Traits\CommonHelper;

trait CountryResponse {

    public function country($county) {
        return [
            'country_name' =>$county['country_name'],
            'cities' =>(new UserRepository())->getCities($county['country_name'])
        ];
    }


}
