<?php

use App\Traits\CommonHelper;
use \App\Repositories\BoatRepository;
use \App\Repositories\BookingRepository;
use \App\Repositories\SystemSettingRepository;
use Carbon\Carbon;
use App\Models\SystemSettings;
if(!function_exists('setDateFormat')) {
     function setDateFormat($date, $format = "Y-m-d") {
         if($date) {
             return date($format, strtotime($date));
         }
        return null;
    }
}
if(!function_exists('convertTimeStampToDateTime')) {
     function convertTimeStampToDateTime($date, $format = "Y-m-d H:i") {
         if($date) {
             return date($format, strtotime($date));
         }
        return null;
    }
}

if(!function_exists('customMapOnTable')) {
     function customMapOnTable($params,$skip) {
        $result =  collect($params)->filter(function ($value, $key) use($skip) {
            return (!empty($value)  &&  $key !== $skip);
        });
        return count($result) > 0 ? $result->toArray() : [];
    }
}
if(!function_exists('preparePostShareURL')) {
    function prepareShareURL($inputs, $type)
    {
        $share_url = "";
        if (!empty($inputs)) {
            $data_string = $type=='post' ? "post_uuid=" . $inputs['post_uuid'] : "boat_uuid_uuid=" . $inputs['boat_uuid'];
            $encoded_string = base64_encode($data_string);
            // $share_url = config("general.url.staging_url") . ($type == 'post' ? "getPostDetail?" : "getBoatDetail?") . $encoded_string;
            $share_url = "https://dev.boatek.co/" . ($type == 'post' ? "getPostDetail?" : "getBoatDetail?") . $encoded_string;
        }
        return $share_url;
    }
}

if(!function_exists('profileImagesResponse')) {
     function profileImagesResponse($image_key = null,$path) {
        //to do
        $response = null;
        $response['1122'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_1122'] . $image_key : null;
        $response['420'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_420'] . $image_key : null;
        $response['336'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_336'] . $image_key : null;
        $response['240'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_240'] . $image_key : null;
        $response['96'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_96'] . $image_key : null;
        $response['orignal'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . CommonHelper::$s3_image_paths[$path.'_image'] . $image_key : null;
        return $response;
    }
}

if(!function_exists('getTotalBookingOfOwner')) {
    function getTotalBookingOfOwner($user_id,$status=false) {
        $boatIds = (new BoatRepository())->getByColumn($user_id,'user_id')->where('status','active')->pluck('id');
        $booking = (new BookingRepository())->whereIn('boat_id',$boatIds)->count();
        return $booking;
    }
}

if(!function_exists('getBoaterCurrentScheduledEarning')) {
    function getBoaterCurrentScheduledEarning($user_id) {
        $systemSettingRepository = new SystemSettingRepository();

        $setting = $systemSettingRepository->getByColumn(1,'is_active');
        $no_of_week = $setting->withdraw_scheduled_duration ?? 1;

        $start_date = Carbon::now()->format('Y-m-d');
        $end_date = Carbon::now()->startOfWeek(Carbon::SUNDAY)->addWeeks($no_of_week);

        $boatIds = (new BoatRepository())->getByColumn($user_id,'user_id')
            ->where('status','active')
            ->pluck('id');
        return  (new BookingRepository())->paymentReceivedAble($boatIds,$start_date,$end_date);
    }
}
