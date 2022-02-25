<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait BoatServicesResponse
{
    public function ServiceResponse($boatServices){
        return [
            'service_uuid'=>$boatServices['boat_service_uuid'],
            'name'=>$boatServices['name'],
        ];
    }
}