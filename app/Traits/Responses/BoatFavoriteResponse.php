<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait BoatFavoriteResponse
{
    public function boatFavoriteResponse($boatFavorite){
        return [
            'favourite_uuid' =>$boatFavorite['favourite_uuid']
        ];
    }
}
