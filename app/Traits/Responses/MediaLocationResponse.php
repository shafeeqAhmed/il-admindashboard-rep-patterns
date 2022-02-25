<?php

namespace App\Traits\Responses;

trait MediaLocationResponse {

    public function mediaLocationResponse($media) {
        return [
            'location_uuid' => $media['location_uuid'],
            'name' => $media['name'],
            'lat' => $media['lat'],
            'lng' => $media['lng'],
            'country' => $media['country'],
            'city' => $media['city'],
            'street_number' => $media['street_number']
        ];
    }

}
