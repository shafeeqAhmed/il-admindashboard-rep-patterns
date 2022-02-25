<?php

namespace App\Traits\Responses;


use App\Helpers\PathHelper;
use App\Models\Boat;
use App\Repositories\BoatDiscountRepository;
use App\Repositories\BoatPostRepository;
use App\Repositories\BoatServicesRepository;
use App\Repositories\CaptainRepository;
use App\Repositories\MediaLocationRepository;
use App\Traits\CommonHelper;

trait BoatStoryResponse
{
    public function boatStoryResponse($story, $viewed=null){
        return [
            'story_uuid'=>$story['story_uuid'],
            'story_image' => $story['story_image'] ? generateThumbnailsResponse($story['story_image'], 'image_stories') : null,
            'story_video' => $story['story_video'] ? generateThumbnailsResponse($story['story_video'], 'post_video', 'video') : null,
            'boat_id' => $story['boat']['boat_uuid'],
            'video_url' => (!empty($story['story_video'])) ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths['video_stories_image'] . $story['story_video']: null,
            'caption' => $story['text'],
            'location' => isset($story['location'])? (new MediaLocationRepository())->mediaLocationResponse($story['location']):null,
            'is_seen' => $viewed,
            'created_at' => $story['created_at'],
            'updated_at' => $story['updated_at']
        ];
    }

//    public function generateThumbnails($story, $type){
//        $thumbnails = [];
//        $thumbnails['1122'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_thumb_1122'] : CommonHelper::$s3_image_paths['video_stories_thumb_1122']) . $story[$type] : null;
//        $thumbnails['420'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_thumb_420'] : CommonHelper::$s3_image_paths['video_stories_thumb_420']) . $story[$type] : null;
//        $thumbnails['336'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_thumb_336'] : CommonHelper::$s3_image_paths['video_stories_thumb_336']) . $story[$type] : null;
//        $thumbnails['240'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_thumb_240'] : CommonHelper::$s3_image_paths['video_stories_thumb_240']) . $story[$type] : null;
//        $thumbnails['96'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_thumb_96'] : CommonHelper::$s3_image_paths['video_stories_thumb_96']) . $story[$type] : null;
//        $thumbnails['orignal'] = !empty($story[$type]) ? config('paths.s3_cdn_base_url') . ($type=='story_image' ? CommonHelper::$s3_image_paths['image_stories_image'] : CommonHelper::$s3_image_paths['video_stories_image']) . $story[$type] : null;
//        return $thumbnails;
//    }
}
