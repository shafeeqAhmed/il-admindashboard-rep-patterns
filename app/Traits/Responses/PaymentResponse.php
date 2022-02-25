<?php

namespace App\Traits\Responses;

trait PaymentResponse {

    public function prepareTokenResponse($decoded_data) {
        $response['sdk_token'] = !empty($decoded_data->sdk_token) ? $decoded_data->sdk_token : null;
        $response['device_id'] = !empty($decoded_data->device_id) ? $decoded_data->device_id : null;
        return $response;
    }

    public function prepareBookingAuthorizationResponse($data) {
        $response = [];
        if (!empty($data)) {
            $response['merchant_reference'] = !empty($data['merchant_reference']) ? $data['merchant_reference'] : null;
            $response['booking_auth_uuid'] = !empty($data['booking_auth_uuid']) ? $data['booking_auth_uuid'] : null;
        }
        return $response;
    }

    public function prepareCardsResponse($array) {
        $response = [];
        if (!empty($array)) {
            foreach ($array as $key => $data) {
                $response[$key]['card_uuid'] = $data['card_uuid'];
//                $response[$key]['card_holder_name'] = $data['card_holder_name'];
                $response[$key]['card_name'] = $data['card_name'];
                $response[$key]['card_type'] = $data['card_type'];
                $response[$key]['last_digits'] = $data['last_digits'];
                $response[$key]['expiry'] = $data['expiry'];
                $response[$key]['token'] = $data['token'];
            }
        }
        return $response;
    }

}
