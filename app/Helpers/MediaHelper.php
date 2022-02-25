<?php

use App\Helpers\PathHelper;
use Aws\Lambda\LambdaClient;

if(!function_exists('setDateFormat')) {
    function setDateFormat($date, $format = "Y-m-d") {
        if($date) {
            return date($format, strtotime($date));
        }
        return null;
    }
}

function processThumbnails($media_key = null, $media_category = null) {

    if ($media_category == 'profile_image') {
        return processImageThumbnails('uploads/profile_images/freelancers/' . $media_key);
    } elseif ($media_category == 'profile_image') {
        return processImageThumbnails('uploads/profile_images/customers/' . $media_key);
    } elseif ($media_category == 'cover_image') {
        return processImageThumbnails('uploads/cover_images/freelancers/' . $media_key);
    } elseif ($media_category == 'cover_image') {
        return processImageThumbnails('uploads/cover_images/customers/' . $media_key);
    } elseif ($media_category == 'cover_image') {
        return processImageThumbnails('uploads/cover_images/customers/' . $media_key);
    } elseif ($media_category == 'image_stories') {
        return processImageThumbnails('uploads/stories/image_stories/' . $media_key);
    } elseif ($media_category == 'post_video') {
        return processVideoThumbnails('uploads/posts/post_videos/' . $media_key);
    } elseif ($media_category == 'message_video') {
        return processVideoThumbnails('uploads/message_attachments/' . $media_key);
    } elseif ($media_category == 'video_stories') {
        return processVideoThumbnails('uploads/stories/video_stories/' . $media_key);
    }
    $response['success'] = false;
    $response['data']['errorMessage'] = 'Could not process image thumbnails';
    return $response;
}

function processImageThumbnails($image_key = null) {

    $client = LambdaClient::factory([
        'version' => 'latest',
        'region' => config('paths.s3_bucket_region'),
        'credentials' => [
            'key'    => config('paths.s3_access_key'),
            'secret' => config('paths.s3_secret_key')
        ]
    ]);

    $result = $client->invoke([
        // The name your created Lamda function
        'InvocationType' => 'RequestResponse',
        'FunctionName' => 'resizeImages',
        'Payload' => json_encode(["s3_key" => $image_key])
//            'Payload' => json_encode(["s3_key" => "uploads/profile_images/customers/0010CD16-FFEB-490B-9FBE-38F3E3096413-1.jpeg"])
    ]);
    $response = json_decode($result->get('Payload')->getContents(), true);
    if (empty($response)) {
        return ['success' => true, 'data' => []];
    }
    return ['success' => false, 'data' => $response];
}

function processVideoThumbnails($video_key = null) {
    $client = LambdaClient::factory([
        'version' => 'latest',
        'region' => config('paths.s3_bucket_region'),
        'credentials' => [
            'key'    => config('paths.s3_access_key'),
            'secret' => config('paths.s3_secret_key')
        ]
    ]);
    $result = $client->invoke([
        // The name your created Lamda function
        'InvocationType' => 'RequestResponse',
        'FunctionName' => 'makeThumbs',
        'Payload' => json_encode(["s3_key" => $video_key])
    ]);
    $response = json_decode($result->get('Payload')->getContents(), true);
    if (empty($response)) {
        return ['success' => true, 'data' => []];
    }
    return ['success' => false, 'data' => $response];
}

if(!function_exists('generateThumbnailsResponse')) {
    function generateThumbnailsResponse($image_key = null,$path, $type = 'image', $id = null) {
        $file = substr($image_key, 0, strrpos($image_key, ".")).'.jpg';
        $response = null;
        $response['1212'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_1212'].$file : null;
        $response['1122'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_1122'].$file : null;
        $response['420'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_420'].$file : null;
        $response['336'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_336'].$file : null;
        $response['240'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_240'].$file : null;
        $response['96'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_96'].$file : null;
        $response['orignal'] = $type== 'video' ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_thumb_1212'].$file: config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths['mobile_uploads'] . $image_key;
        $response['image_name'] =  $image_key;
        $response['image_uuid'] = $id;
//        $response['orignal'] = !empty($image_key) ? config('paths.s3_cdn_base_url') . PathHelper::$s3_image_paths[$path.'_image'] . $image_key['story_image'] : null;
        return $response;
    }
}
