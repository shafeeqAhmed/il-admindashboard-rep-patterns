<?php

namespace App\Traits;

use DateTime;

use Carbon\Carbon;
use Mail;

Class CommonHelper {
    /*
      |--------------------------------------------------------------------------
      | CommonHelper that contains all the common methods for APIs
      |--------------------------------------------------------------------------
      |
      | This Helper controls all the methods that use common processes
      |
     */

    public static $s3_image_paths = [
        'directooo_general' => 'mobileUploads/',
        'mobile_uploads' => 'mobileUploads/',
        'general' => 'uploads/general/',
        'category_image' => 'uploads/category_images/',
        'profile_image' => 'uploads/profile_images/users/',
        'freelancer_profile_image' => 'uploads/profile_images/freelancers/',
        'profile_thumb_1122' => 'uploads/profile_images/users/1122/',
        'profile_thumb_420' => 'uploads/profile_images/users/420/',
        'profile_thumb_336' => 'uploads/profile_images/users/336/',
        'profile_thumb_240' => 'uploads/profile_images/users/240/',
        'profile_thumb_96' => 'uploads/profile_images/users/96/',
        'freelancer_cover_image' => 'uploads/cover_images/freelancers/',
        'company_logo' => 'uploads/company_logos/company_logo/',
        'customer_profile_image' => 'uploads/profile_images/customers/',
        'customer_profile_thumb_1122' => 'uploads/profile_images/customers/1122/',
        'customer_profile_thumb_420' => 'uploads/profile_images/customers/420/',
        'customer_profile_thumb_336' => 'uploads/profile_images/customers/336/',
        'customer_profile_thumb_240' => 'uploads/profile_images/customers/240/',
        'customer_profile_thumb_96' => 'uploads/profile_images/customers/96/',
        'customer_cover_image' => 'uploads/cover_images/customers/',
        'cover_video' => 'uploads/videos/cover_videos/',
        'post_image' => 'uploads/posts/post_images/',
        'post_thumb_1122' => 'uploads/posts/post_images/1122/',
        'post_thumb_420' => 'uploads/posts/post_images/420/',
        'post_thumb_336' => 'uploads/posts/post_images/336/',
        'post_thumb_240' => 'uploads/posts/post_images/240/',
        'post_thumb_96' => 'uploads/posts/post_images/96/',
        'post_video' => 'uploads/posts/post_videos/',
        'post_video_thumb_1212' => 'uploads/posts/post_videos/1212/',
        'post_video_thumb_1122' => 'uploads/posts/post_videos/1122/',
        'post_video_thumb_420' => 'uploads/posts/post_videos/420/',
        'post_video_thumb_336' => 'uploads/posts/post_videos/336/',
        'post_video_thumb_240' => 'uploads/posts/post_videos/240/',
        'post_video_thumb_96' => 'uploads/posts/post_videos/96/',
//        'post_video_thumb' => 'uploads/posts/post_videos_thumb/',
        'post_video_thumb' => 'uploads/posts/post_videos/1212/',
        'package_image' => 'uploads/packages/package_image/',
        'folder_images' => 'uploads/folders/folder_image/',
        'image_stories_image' => 'uploads/stories/image_stories/',
        'image_stories_thumb_1122' => 'uploads/stories/image_stories/1122/',
        'image_stories_thumb_420' => 'uploads/stories/image_stories/420/',
        'image_stories_thumb_336' => 'uploads/stories/image_stories/336/',
        'image_stories_thumb_240' => 'uploads/stories/image_stories/240/',
        'image_stories_thumb_96' => 'uploads/stories/image_stories/96/',
        'video_stories_image' => 'uploads/stories/video_stories/',
        'video_stories_thumb_1122' => 'uploads/stories/video_stories/1122/',
        'video_stories_thumb_420' => 'uploads/stories/video_stories/420/',
        'video_stories_thumb_336' => 'uploads/stories/video_stories/336/',
        'video_stories_thumb_240' => 'uploads/stories/video_stories/240/',
        'video_stories_thumb_96' => 'uploads/stories/video_stories/96/',
        'gym_logo' => 'uploads/logos/gym_logo/',
        'class_images' => 'uploads/classes/',
        'freelancer_category_image' => 'uploads/freelancer_category_images/',
        'freelancer_category_video' => 'uploads/freelancer_category_videos/',
        'package_description_video' => 'uploads/packages/package_description_video/',
        'class_description_video' => 'uploads/classes/class_description_video',
        'message_attachments' => 'uploads/message_attachments/',
        'video_thumbnail' => 'uploads/video_thumbnail/',
    ];

    public static function uploadSingleImage($file, $s3_destination, $pre_fix = '', $server = 's3') {
        $full_name = $pre_fix . uniqid() . time() . '.' . $file->getClientOriginalExtension();
        $upload = $file->storeAs($s3_destination, $full_name, $server);
        if ($upload) {
            return ['success' => true, 'file_name' => $full_name];
        }
        return ['success' => false, 'file_name' => ''];
    }

    public function getTimeDifferenceInMinutes($start_time, $end_time) {
        $mintues = round(abs($end_time - $start_time) / 60, 2);
        return $mintues;
    }

    public static function convertUnixToDateTime($unix){
        return Carbon::createFromTimestamp($unix)->toDateString();
    }

    public static function convertDateTimeFormat($date){
        return Carbon::createFromDate($date)->toDateString();
    }

    public static function sendEmail($user, $data, $subject, $view){
        Mail::send('email.'.$view, ['data' => $data], function ($m) use ($user, $subject) {
            $m->from('kaleemsofttest@gmail.com', $subject);

            $m->to($user['email'], $user['first_name'])->subject($subject);
        });
    }
    public static function getTimeDifferenceInHours($start_time, $end_time) {
        $hours = round(abs($end_time - $start_time) / 3600, 2);
        return $hours;
    }

    public function convertDateTimeToTimeZone($date, $from_timezone = 'UTC', $to_timezone = 'UTC') {
        $date = new DateTime($date, new \DateTimeZone($from_timezone));
        $date->setTimezone(new \DateTimeZone($to_timezone));
        return $date->format('Y-m-d H:i:s'); // 2020-8-13 10:20:00
    }

    public function checkDateHours($date, $hour){
        $new_date = date("Y-m-d H:i:s", strtotime('+'.$hour. ' hour',strtotime($date)));
        $current_date = date("Y-m-d H:i:s");
        if (strtotime($new_date) > strtotime($current_date)){
            return false;
        }
        return true;
    }

    public function convertDateTimeToLocalTimezone($date, $default_timezone, $local_timezone) {
        return Carbon::parse(date('d-m-Y h:i a', strtotime($date))  .' '. $default_timezone)->tz($local_timezone)->format('d-m-Y h:i a');
    }
    public function convertUTCDateTimeToLocalTimezone($date, $default_timezone, $local_timezone,$format) {
        return Carbon::parse(date('d-m-Y h:i a', $date)  .' '. $default_timezone)->tz($local_timezone)->format($format);
    }

}

?>
