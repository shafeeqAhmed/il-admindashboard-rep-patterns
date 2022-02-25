<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;

use App\Traits\CommonHelper;

trait CityResponse {

    public function city($county) {
        return [
            'city_name' =>$county['city'],
        ];
    }


}
