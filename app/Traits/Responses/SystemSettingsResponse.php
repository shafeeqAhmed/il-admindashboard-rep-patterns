<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;

use App\Traits\CommonHelper;

trait SystemSettingsResponse {

    public function systemSettingsResponse($settings) {
        return [
            'settings_uuid' =>$settings['system_setting_uuid'],
            'vat' => $settings['vat'],
            'boat_charges' => $settings['boatek_commission_charges'],
            'transaction_charges' => $settings['transaction_charges'],
        ];
    }


}
