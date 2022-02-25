<?php

namespace App\Helpers;


Class PathHelper {
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
        'freelancer_profile_image' => 'uploads/profile_images/freelancers/',
        'freelancer_profile_thumb_1122' => 'uploads/profile_images/freelancers/1122/',
        'freelancer_profile_thumb_420' => 'uploads/profile_images/freelancers/420/',
        'freelancer_profile_thumb_336' => 'uploads/profile_images/freelancers/336/',
        'freelancer_profile_thumb_240' => 'uploads/profile_images/freelancers/240/',
        'freelancer_profile_thumb_96' => 'uploads/profile_images/freelancers/96/',
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
        'post_image_image' => 'uploads/posts/post_images/',
        'post_video_thumb_1212' => 'uploads/posts/post_videos/1212/',
        'post_image_thumb_1122' => 'uploads/posts/post_images/1122/',
        'post_image_thumb_420' => 'uploads/posts/post_images/420/',
        'post_image_thumb_336' => 'uploads/posts/post_images/336/',
        'post_image_thumb_240' => 'uploads/posts/post_images/240/',
        'post_image_thumb_96' => 'uploads/posts/post_images/96/',

        'post_video_image' => 'uploads/posts/post_videos/',
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

}

?>
