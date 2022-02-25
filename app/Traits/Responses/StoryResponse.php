<?php

namespace App\Traits\Responses;

trait StoryResponse {

    public function storyResponse($boat, $user_viewed) {
        $response = [];
        if (!empty($boat)) {

            foreach ($boat as $key => $value) {
                $response[$key]['boat_uuid'] = $value['boat_uuid'];
                $response[$key]['boat_name'] = !empty($value['name']) ? $value['name'] : null;
                $response[$key]['boat_number'] = !empty($value['number']) ? $value['number'] : null;
                $response[$key]['boat_manufacturer'] = $value['manufacturer'];
                $response[$key]['boat_location'] = $value['location'];
                $response[$key]['boat_lat'] = $value['lat'];
                $response[$key]['boat_lng'] = $value['lng'];
                $response[$key]['boat_state'] = $value['state'];
                $response[$key]['boat_country'] = $value['country'];
                $response[$key]['price_per_hour'] = $value['price'];
                $response[$key]['boat_onboard_name'] = !empty($value['onboard_name']) ? $value['onboard_name'] : null;
                $response[$key]['boat_profile_pic'] = !empty($value['profile_pic']) ? $value['profile_pic'] : null;
                $response[$key]['boat_capacity'] = !empty($value['capacity']) ? $value['capacity'] : null;
                $response[$key]['boat_info'] = !empty($value['info']) ? $value['info'] : null;
                $response[$key]['stories'] = !empty($value['active_stories']) ? $this->processStoriesResponse($value['active_stories'], $user_viewed) : [];
            }
        }
        return $response;
    }

    public function processStoriesResponse($data = [], $user_viewed = []) {
        $response = [];
        $user_viewed = collect($user_viewed);
        if (!empty($data)) {
            foreach ($data as $key => $value) {

                $viewed = false;
                if(!empty($user_viewed)){
                    $viewed = $user_viewed->first(function ($val) use($value){
                        return $val->story_id == $value['id'];
                    });
                    $viewed = (bool)$viewed;
                }

                $response[$key]['story_uuid'] = $value['story_uuid'];
                $response[$key]['text'] = !empty($value['text']) ? $value['text'] : null;
                $response[$key]['story_image'] = $value['story_image'] ? generateThumbnailsResponse($value['story_image'], 'image_stories') : null;
                $response[$key]['story_video'] = $value['story_video'] ? generateThumbnailsResponse($value['story_video'], 'video_stories') : null;
//                $response[$key]['story_image'] = !empty($value['story_image']) ? $value['story_image'] : null;
//                $response[$key]['story_video'] = !empty($value['story_video']) ? $value['story_video'] : null;
                $response[$key]['time_ago'] = $this->convertDateToTimeZone($value['created_at']);
                $response[$key]['video_thumbnail'] = null;
                $response[$key]['is_seen'] = $viewed;
            }
        }
        return $response;
    }

    public static function convertDateToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new \DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d h:i:s'); // 2020-8-13
    }
}
