<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;


use App\Repositories\BoatPostRepository;
use App\Repositories\UserRepository;

trait PostLikeResponse
{
    public function postLikeResponse($postLike){
        return [
            'like_uuid' =>$postLike['like_uuid'],
            'liked_at' => date( 'Y-m-d', strtotime($postLike['created_at'])),
            'post' => (new BoatPostRepository())->postResponse($postLike['post']),
            'user' => (new UserRepository())->userResponse($postLike['user']),
        ];
    }
}
