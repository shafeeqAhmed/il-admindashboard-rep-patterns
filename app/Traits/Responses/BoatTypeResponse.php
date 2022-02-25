<?php

namespace App\Traits\Responses;

trait BoatTypeResponse {

    public function BoatTypeResponse($type) {
        return [
            'boat_type_uuid' => $type['boat_type_uuid'],
            'name' => $type['name'],
            'pic' => $type['pic'],
        ];
    }

}
