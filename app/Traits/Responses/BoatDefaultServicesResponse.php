<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait BoatDefaultServicesResponse
{
    public function BoatDefaultServiceResponse($defaultBoatServices){
        return [
            'boat_default_service_uuid'=>$defaultBoatServices['boat_default_service_uuid'],
            'name'=>$defaultBoatServices['name'],
        ];
    }
}
