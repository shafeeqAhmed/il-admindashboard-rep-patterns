<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


use Carbon\Carbon;

trait PromoCodeResponse
{
    public function promoCodeResponse($promoCode){
        return [
            'code_uuid' => $promoCode['code_uuid'],
            'start_date' => Carbon::createFromTimestamp($promoCode['valid_from'])->format('Y-m-d'),
            'end_date' => Carbon::createFromTimestamp($promoCode['valid_to'])->format('Y-m-d'),
            'coupon_code' => $promoCode['coupon_code'],
            'percentage' => floatval($promoCode['coupon_amount']),

        ];
    }
}
