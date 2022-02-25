<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


trait BoatReviewResponse
{
    public function boatReviewResponse($boatReview){
            return [
                "review_uuid"=> $boatReview['review_uuid'],
                "rating" =>floatval($boatReview['rating']),
                "review" =>$boatReview['review'],
                "reply" =>!empty($boatReview['reply']) ? $boatReview['reply'] : null,
                'user_name'=> $boatReview['user']['first_name'],
                'profile_pic'=> $boatReview['user']['profile_pic'],
                'crated_at'=> setDateFormat($boatReview['created_at']),
                'reply_at'=> setDateFormat($boatReview['updated_at'])
            ];
    }

}
