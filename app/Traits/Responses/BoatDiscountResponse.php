<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


use App\Models\Boat;
use App\Repositories\BoatDiscountRepository;
use App\Repositories\BoatServicesRepository;
use App\Repositories\CaptainRepository;

trait BoatDiscountResponse
{
    public function boatDiscountResponse($boat){

       return [
            'discount_uuid'=>$boat['discount_uuid'] ,
            'after'=>$boat['discount_after'],
            'percent'=>$boat['percentage'],
         ];
    }

    public function getBoatIdByUuid($uuid){
        return Boat::where('boat_uuid',$uuid)->first()->id;
    }

}
