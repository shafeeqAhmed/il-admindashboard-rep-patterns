<?php
/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/29/2021
 * Time: 11:03 AM
 */

namespace App\Traits\Responses;
use App\Helpers\PathHelper;


use App\Models\Boat;
use App\Repositories\BoatRepository;
use App\Repositories\MediaLocationRepository;
use App\Repositories\PostLikeRepository;

trait PostResponse
{

    public function postResponse($post,$user_id=false){
        return [
            'post_uuid'=> $post['post_uuid'],
            'caption'=> $post['caption'],
            'post_media_type'=> $post['media_type'],
            'post_status'=> $post['status'],
            'post_image' => (!empty($post['src'])) ? generateThumbnailsResponse($post['src'], 'post_'.$post['media_type'], $post['media_type']) : null,
            'video_url' => (!empty($post['src']) && $post['media_type'] == 'video') ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths['post_video_image'] . $post['src']: null,
            'boat' => isset($post['boat'])?(new BoatRepository())->getBoatDetail($post['boat']):null,
            'location' => isset($post['location'])? (new MediaLocationRepository())->mediaLocationResponse($post['location']):null,
            'is_liked' => ($user_id)?(new PostLikeRepository())->checkPostLiked($user_id, $post['id']):null,
            'likes_count' => (isset($post['likes_count']))? $post['likes_count']:null
        ];
    }

    public function getBoatIdByUuid($uuid){
        return Boat::where('boat_uuid',$uuid)->first()->id;
    }

}
