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
use App\Repositories\BoatDiscountRepository;
use App\Repositories\BoatDocumentRepository;
use App\Repositories\BoatFavoriteRepository;
use App\Repositories\BoatPostRepository;
use App\Repositories\BoatRepository;
use App\Repositories\BoatReviewRepository;
use App\Repositories\BoatServicesRepository;
use App\Repositories\BookingRepository;
use App\Repositories\CaptainRepository;
use App\Repositories\PostLikeRepository;
use App\Repositories\ReviewRatingRepository;
use App\Repositories\BoatStoryRepository;

trait BoatResponse {

    public function boatResponse($boat, $userId = false) {
        $response = [];
        if (!empty($boat)) {
        return [
            'boat_uuid' => $boat['boat_uuid'],
            'is_approved' => $boat['is_approved'],
            'boat_name' => !empty($boat['name']) ? $boat['name'] : null,
            'boat_number' => !empty($boat['number']) ? $boat['number'] : null,
            'boat_manufacturer' => $boat['manufacturer'],
            'boat_type_uuid' => $boat['boat_type']['boat_type_uuid'],
            'boat_type_name' => $boat['boat_type']['name'],
            'boat_onboard_name' => $boat['onboard_name'],
            'boat_default_pic'=> $boat['profile_pic'],
            'boat_images' => (!empty($boat['boat_images'])) ? $this->makeBoatMultipleImages($boat['boat_images']) : null,
            'boat_capacity' => $boat['capacity'],
            'boat_info' => $boat['info'],
            'boat_location' => $boat['location'],
            'is_active' => $boat['is_active'],
            'boat_lat' => $boat['lat'],
            'boat_lng' => $boat['lng'],
            'boat_state' => $boat['state'],
            'boat_country' => $boat['country'],
            'price_per_hour' => $boat['price'] * 2,
            'distance' => !empty($boat['distance']) ? round($boat['distance'], 2) : 0,
            'total_tours' => $this->getTotalTours($boat['boat_uuid']),
            'boat_rating' => floatval($this->getBoatRating($boat['boat_uuid'])),
            'is_favorite' => (isset($userId)) ? (new BoatFavoriteRepository())->checkBoatLiked($userId, $boat['id']) : null,
            'discount' => (isset($boat['discount'])) ? (new BoatDiscountRepository())->makeMultiResponse($boat['discount']) : null,
            'services' => (isset($boat['boat_services'])) ? (new BoatServicesRepository)->makeMultiResponse($boat['boat_services']) : null,
            'captains' => (isset($boat['boat_captains'])) ? (new CaptainRepository())->makeMultiResponse($boat['boat_captains']) : null,
            'posts' => (isset($boat['posts'])) ? (new BoatPostRepository())->makeMultiResponse($boat['posts']) : null,
            'reviews' => (isset($boat['reviews'])) ? (new BoatReviewRepository())->makeMultiResponse(!empty($boat['reviews']) ? $boat['reviews'] : []) : null,
            'boat_documents' => (isset($boat['boat_documents']) && (!empty($boat['boat_documents']))) ? (new BoatDocumentRepository())->makeMultipleResponse($boat['boat_documents']) : [],
            'boat_share_url' => prepareShareURL($boat, 'boat'),
            'stories' => (isset($boat['stories'])) ? (new BoatStoryRepository())->makeMultiResponse(!empty($boat['stories']) ? $boat['stories'] : [], $userId) : null,
        ];
       }

       return $response;
    }

    public function makeBoatMultipleImages($images) {
        $all_images = [];
        foreach ($images as $image) {
            $all_images[] = generateThumbnailsResponse($image['url'], 'mobile_uploads', 'image', $image['boat_image_uuid']);
        }
        return $all_images;
    }

    public function getTotalTours($boat_uuid) {
        $bookingRepository = new BookingRepository();
        return $bookingRepository->getBoatTotalTours($this->getByColumn($boat_uuid, 'boat_uuid')->id);
    }

    public function getBoatRating($boat_uuid) {
        $bookingRepository = new ReviewRatingRepository();
        return $bookingRepository->getBoatRating($this->getByColumn($boat_uuid, 'boat_uuid')->id);
    }

    public function getBoatIdByUuid($uuid) {
        return Boat::where('boat_uuid', $uuid)->first()->id;
    }

}
