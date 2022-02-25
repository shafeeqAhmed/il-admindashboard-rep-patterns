<?php

/**
 * Created by PhpStorm.
 * User: ILSA Interactive
 * Date: 12/27/2021
 * Time: 6:17 PM
 */

namespace App\ValidateRequest;

use App\ValidateRequest\ValidatorInterface\ValidatorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateRequest implements ValidatorInterface {

    public function validate(Request $request, $rules, $message) {

        $validator = Validator::make($request->all(), $rules, $message);
        return $validator;
    }

    public function userSignUp($params) {
        return [
            'rules' => [
                'password' => 'required',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required',
                'country_code' => 'required',
                'country_name' => 'required',
                'device_token' => 'required',
                'device_type' => 'required',
            ],
            'messages' => [
                'email.required' => 'walla your email is missing',
                'first_name.required' => 'your first name is missing',
                'last_name.required' => 'your last name is missing',
                'password.required' => 'your password is missing',
                'phone_number.required' => 'phone number is missing',
                'country_code.required' => 'country code is missing',
                'country_name.required' => 'country name is missing',
                'device_token.required' => 'device token is missing',
                'device_type.required' => ' device type is required',
            ]
        ];
    }

    public function filterBoats($params) {

    }

    public function loginRequest($params) {
        return [
            'rules' => [
                'login_user_type' => 'required',
                'phone_number' => 'required',
                'password' => 'required'
            ],
            'messages' => [
                'login_user_type.required' => ' login user type is missing',
                'phone_number.required' => ' your phone number is missing',
                'password.required' => 'password is missing'
            ]
        ];
    }

    public function logoutRequest() {
        return [
            'rules' => [
                'user_uuid' => 'required',
                'device_token' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is missing',
                'device_token.required' => ' device token is missing',
            ]
        ];
    }

    public function removeBoat() {
        return [
            'rules' => [
                'boat_uuid' => 'required'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is missing'
            ]
        ];
    }

    public function getNotifications() {
        return [
            'rules' => [
                'boat_uuid' => 'sometimes|required|exists:boats,boat_uuid',
                'user_uuid' => 'sometimes|required|exists:users,user_uuid',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is missing',
                'user_uuid.required' => 'user uuid is missing'
            ]
        ];
    }

    public function createBoat($params) {

        if (isset($params['boat_uuid'])) {
            return [
                'rules' => [
                    'boat_boat_type_uuid' => 'required',
                    'boat_location' => 'required',
                    'boat_onboard_name' => 'required',
                    'boat_state' => 'required',
                    'boat_country' => 'required',
                    'boat_uuid' => 'required',
                    'boat_lat' => 'required',
                    'boat_lng' => 'required',
                ],
                'messages' => [
                    'boat_boat_type_id.required' => 'boat type is missing',
                    'boat_location.required' => 'location is required',
                    'boat_onboard_name.required' => 'board name is required',
                    'boat_state.required' => 'state value is required',
                    'boat_country.required' => 'country value is required',
                    'boat_uuid.required' => 'boat uuid value is required',
                    'boat_lat.required' => 'lat value is required',
                ]
            ];
        } else {
            return [
                'rules' => [
                    'boat_boat_type_uuid' => 'required',
                    'boat_location' => 'required',
                    'boat_onboard_name' => 'required',
                    'boat_state' => 'required',
                    'boat_country' => 'required',
                    'boat_lat' => 'required',
                    'boat_lng' => 'required',
                    'boat_profile_pic' => 'required',
                ],
                'messages' => [
                    'boat_name_number.required' => 'Boat Name required',
                    'boat_manufacturer.required' => 'manufacturer name is missing',
                    'boat_boat_type_id.required' => 'boat type is missing',
                    'boat_profile_pic.required' => 'profile picture is missing',
                    'boat_capacity.required' => 'boat capacity is missing',
                ]
            ];
        }
    }

    public function addServices($params) {


        return [
            'rules' => [
                'custom_services.*.name' => 'sometimes|required',
                'default_services.*' => 'sometimes|required|exists:boat_default_services,boat_default_service_uuid',
                'boat_onboard_name' => 'required',
                'boat_uuid' => 'required',
            ],
            'messages' => [
            ]
        ];
    }

    public function deleteServices($params) {


        return [
            'rules' => [
                'service_uuid' => 'required',
            ],
            'messages' => [
            ]
        ];
    }

    public function updateBoatLocation() {
        return [
            'rules' => [
//                'boat_name' => 'required',
//                'boat_number' => 'required',
//                'boat_manufacturer' => 'required',
            ],
            'messages' => [
//                'boat_name.required' => 'Boat Name required',
//                'boat_number.required' => 'Boat Number required',
//                'boat_manufacturer.required' => 'Manufacturer name is missing',
            ]
        ];
    }

    public function addCaptain($params) {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'boat_onboard_name' => 'required',
                'captain.*.name' => 'required',
                'captain.*.image' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
                'boat_onboard_name.required' => 'boat_onboard_name is required',
                'name.required' => 'name is required',
                'image.required' => 'images is required',
            ]
        ];
    }

    public function addBoatPrice() {

        return [
            'rules' => [
                'boat_onboard_name' => 'required',
                'boat_uuid' => 'required',
                'price_per_hour' => 'required',
                'discount.*.after' => 'required',
                'discount.*.percent' => 'required',
            ],
            'messages' => [
                'boat_onboard_name.required' => 'boat_onboard_name is required',
                'boat_uuid.required' => 'boat uuid is required',
                'price_per_hour.required' => 'price per hour is required',
                'after.required' => 'after value is required',
                'percent.required' => 'percentage value is required',
            ]
        ];
    }

    public function getUserBoats() {

        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
            ]
        ];
    }

    public function addContent() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
                'media_type' => 'required',
                'media' => 'required',
                'type' => 'required'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is missing',
                'media_type.required' => ' media type is missing',
            ]
        ];
    }

    public function getBoatStories() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is missing'
            ]
        ];
    }

    public function addStoryView(){
        return [
            'rules' => [
                'story_uuid' => 'required|exists:boat_stories,story_uuid',
                'user_uuid' => 'required|exists:users,user_uuid'
            ],
            'messages' => [
                'story_uuid.required' => 'story uuid is missing',
                'user_uuid.required' => 'user uuid is missing'
            ]
        ];
    }

    public function getSingleStory() {
        return [
            'rules' => [
                'story_uuid' => 'required|exists:boat_stories'
            ],
            'messages' => [
                'story_uuid.required' => 'story uuid is missing'
            ]
        ];
    }

    public function getBookingDetail() {
        return [
            'rules' => [
                'booking_uuid' => 'required'
            ],
            'messages' => [
                'booking_uuid.required' => 'booking uuid is missing',
            ]
        ];
    }

    public function createBoatReview($params) {

        return [
            'rules' => [
                'boat_uuid' => 'required',
                'user_uuid' => 'required',
                'rating' => 'required',
                'review' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'Boat uuid is missing',
                'user_uuid.required' => 'User uuid is missing',
                'rating.required' => 'Rating is missing',
                'review.required' => 'review is missing',
            ]
        ];
    }

    public function createBoatReviewReply($params) {
        return [
            'rules' => [
                'review_uuid' => 'required',
                'reply' => 'required',
            ],
            'messages' => [
                'review_uuid.required' => 'Review uuid is missing',
                'reply.required' => 'Review uuid is missing',
            ]
        ];
    }

    public function getStories($params) {

        return [
            'rules' => [
//                'login_user_type' => 'required',
            ],
            'messages' => [
//                'login_user_type.required' => 'login user type is required',
            ]
        ];
    }

    public function getBoatsWithType($params) {

        return [
            'rules' => [
//                'boat_type_uuid' => 'required',
            ],
            'messages' => [
//                'boat_type_uuid.required' => 'boat type uuid is required',
            ]
        ];
    }

    public function getBoatCalender() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function getBoatDashboard() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function getBoatBookings() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
                'status' => 'required',
                'offset' => 'required',
                'limit' => 'required'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
                'status.required' => 'status is required',
            ]
        ];
    }

    public function searchMapBoats($params) {

        return [
            'rules' => [
//                'boat_type_uuid' => 'required',
            ],
            'messages' => [
//                'boat_type_uuid.required' => 'boat type uuid is required',
            ]
        ];
    }

    public function bookBoat() {
        return [
            'rules' => [
                'start_date_time' => 'required',
                'end_date_time' => 'required',
                'boat_uuid' => 'required',
                'user_uuid' => 'required',
                'saved_timezone' => 'required',
                'booking_price' => 'required',
                // 'card_uuid' => 'required',
                'local_timezone' => 'required',
                'payment_received' => 'required',
            ],
            'messages' => [
                'start_date_time.required' => 'start_date_time is missing',
                'end_date_time.required' => 'end_date_time is missing',
                'boat_uuid.required' => 'boat_uuid is missing',
                'user_uuid.required' => 'user_uuid is missing',
                'saved_timezone.required' => 'saved_timezone is missing',
                'booking_price.required' => 'booking_price is missing',
            // 'card_uuid.required' => 'card_id is missing',
            ]
        ];
    }

    public function addBoatFavorite($params) {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'user_uuid' => 'required',
                'type' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'Boat uuid is missing',
                'user_uuid.required' => 'User uuid is missing',
                'type.required' => 'Favorite Type(favorite/unfavorite) is missing',
            ]
        ];
    }

    public function createBoatService($params) {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'name' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'Boat uuid is missing',
                'name.required' => 'Name is missing',
            ]
        ];
    }

    public function getUserPersonalInformation($params) {
        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'User uuid is missing',
            ]
        ];
    }

    public function updateUser($params) {
        return [
            'rules' => [
                'user_uuid' => 'required',
                'email' => 'email|nullable',
            ],
            'messages' => [
                'user_uuid.required' => 'User uuid is missing',
            ]
        ];
    }

    public function getRequestToken($params) {

        return [
            'rules' => [
                'device_id' => 'required',
            ],
            'messages' => [
                'device_id.required' => 'device id is required',
            ]
        ];
    }

    public function getCustomerDashboard($params) {
        return [
            'rules' => [
                'customer_uuid' => 'required',
            ],
            'messages' => [
                'customer_uuid.required' => 'customer uuid is required',
            ]
        ];
    }

    public function getCustomerBooking($params) {
        return [
            'rules' => [
                'customer_uuid' => 'required',
            ],
            'messages' => [
                'customer_uuid.required' => 'customer uuid is required',
            ]
        ];
    }

    public function saveAuthorizationData($params) {
        return [
            'rules' => [
                'remember_me' => 'required',
                'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'fort_id' => 'required',
                'merchant_reference' => 'required',
                'user_uuid' => 'required'
            ],
            'messages' => [
                'remember_me.required' => 'remember me field is required',
                'amount.required' => 'amount is required',
                'fort_id.required' => 'fort id is required',
                'merchant_reference.required' => 'merchant reference is required',
                'user_uuid.required' => 'user uuid is required',
//                'login_user_type.required' => 'login user type is required',
            ]
        ];
    }

    public function getBoatSettings() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function getPromoCodes() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function checkPromoCodeValidity() {
        return [
            'rules' => [
                'code' => 'required',
                'user_uuid' => 'required',
                'boat_uuid' => 'required',
            ],
            'messages' => [
                'code_uuid.required' => 'code uuid is required',
            ]
        ];
    }

    public function getPromoCodesByStatus() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function addPromoCode() {
        return [
            'rules' => [
                'boat_uuid' => 'required|exists:boats',
                'code' => 'required|unique:promo_codes,coupon_code',
                'start_date' => 'required',
                'end_date' => 'required',
                'percentage' => 'required'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
                'code.required' => 'coupon code is required',
                'start_date.required' => 'start time is required',
                'end_date.required' => 'end time is required',
                'percentage.required' => 'percentage is required'
            ]
        ];
    }

    public function getPromocodeDetail() {
        return [
            'rules' => [
                'code_uuid' => 'required|exists:promo_codes',
            ],
            'messages' => [
                'code_uuid.required' => 'code uuid is required',
            ]
        ];
    }

    public function getCardsList($params) {
        return [
            'rules' => [
                'user_uuid' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
            ]
        ];
    }

    public function deleteCard($params) {
        return [
            'rules' => [
                'user_uuid' => 'required',
                'card_uuid' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
                'card_uuid.required' => 'card uuid is required',
            ]
        ];
    }

    public function removePromocode() {
        return [
            'rules' => [
                'code_uuid' => 'required|exists:promo_codes',
            ],
            'messages' => [
                'code_uuid.required' => 'code uuid is required',
            ]
        ];
    }

    public function updateBooking() {
        return [
            'rules' => [
                'logged_in_uuid' => 'required',
                'login_user_type' => 'required',
                'booking_uuid' => 'required',
                'status' => 'required',
//                'local_timezone' => 'required',
//                'currency' => 'required'
            ],
            'messages' => [
                'logged_in_uuid.required' => 'logged in uuid is required',
                'login_user_type.required' => 'login user type is required',
                'booking_uuid.required' => 'booking uuid  is required',
                'status.required' => 'status is required',
//                'local_timezone.required' => 'local_timezone is required',
            ]
        ];
    }

    public function updateStatus() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'booking_uuid' => 'required',
                'status' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid  is required',
                'booking_uuid.required' => 'booking uuid  is required',
                'status.required' => 'status is required',
            ]
        ];
    }

    public function getTransactions() {
        return [
            'rules' => [
                'type' => 'required',
                'uuid' => 'required',
                'status' => 'required',
            ],
            'messages' => [
                'type.required' => 'type is required',
                'boat_uuid.required' => 'boat uuid is required',
                'status.required' => 'status is required',
            ]
        ];
    }
    public function getTransactionDetail() {
        return [
            'rules' => [
                'transaction_uuid' => 'required',
            ],
            'messages' => [
                'transaction_uuid.required' => 'transaction uuid is required',
            ]
        ];
    }

    public function getBalance() {
        return [
            'rules' => [
                'type' => 'required',
                'uuid' => 'required',
            ],
            'messages' => [
                'type.required' => 'type is required',
                'uuid.required' => 'uuid is required',
            ]
        ];
    }

    public function getPendingTransactions() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function addBankDetail() {
        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
            ]
        ];
    }

    public function getBankDetail() {
        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
            ]
        ];
    }
    public function transferBalance() {
        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is required',
            ]
        ];
    }
 public function transferBalanceDetail() {
        return [
            'rules' => [
                'withdraw_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'withdraw uuid is required',
            ]
        ];
    }

    public function deleteCaptain() {
        return [
            'rules' => [
                'captain_uuid' => 'required',
            //  'boat_uuid' => 'required',
            ],
            'messages' => [
                'captain_uuid.required' => 'captain uuid is required',
            //     'boat_uuid.required' => 'boat uuid is required',
            ]
        ];
    }

    public function updateCaptain() {
        return [
            'rules' => [
                'captain_uuid' => 'required',
                'captain.*.first_name' => 'required',
//                'captain.*.image' => 'required',
            ],
            'messages' => [
                'captain_uuid.required' => 'captain uuid is required',
                'name.required' => 'name is required',
                'image.required' => 'image is required',
            ]
        ];
    }

    public function userNotificationSettings($params) {

        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'User uuid is missing',
            ]
        ];
    }

    public function getNotificationSettings(){
        return [
            'rules' => [
                'user_uuid' => 'required|exists:users,user_uuid',
            ],
            'messages' => [
                'user_uuid.required' => 'User uuid is missing',
            ]
        ];
    }

    public function getPostDetail($params) {

        return [
            'rules' => [
                'post_uuid' => 'required|exists:boat_posts,post_uuid',
//                'user_uuid' => 'required|exists:users,user_uuid'
            ],
            'messages' => [
                'post_uuid.required' => 'post uuid is missing',
            ]
        ];
    }

    public function removeContent() {
        return [
            'rules' => [
                'content_uuid' => 'required',
            ],
            'messages' => [
                'content_uuid.required' => 'content uuid is missing',
            ]
        ];
    }

    public function getFavouriteBoat($params) {

        return [
            'rules' => [
                'user_uuid' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'User uuid is missing',
            ]
        ];
    }

    public function updateUserImage() {
        return [
            'rules' => [
                'user_uuid' => 'required',
                'image' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is missing',
                'image.required' => 'image name is missing',
            ]
        ];
    }

    public function getCalendarBookings() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'date' => 'required'
            ],
            'messages' => [
                'boat_uuid.required' => 'boat uuid is missing',
                'date.required' => 'date is missing',
            ]
        ];
    }

    public function boatSchedules() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'date' => 'required',
//                'end_date' => 'required',
                'type' => 'required',
//                'user_uuid' => 'required',
//                'from_time' => 'required',
//                'to_time' => 'required',
            ],
            'messages' => [
            ]
        ];
    }

    public function multiSchedules() {
        return [
            'rules' => [
                'boat_uuid' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                //'type' => 'required',
                'user_uuid' => 'required',
            //'from_time' => 'required',
            //'to_time' => 'required',
            ],
            'messages' => [
            ]
        ];
    }

    public function rescheduleBooking() {
        return [
            'rules' => [
                'user_uuid' => 'required|exists:users',
                'booking_uuid' => 'required|exists:bookings',
                'date' => 'required',
                'from_time' => 'required',
//                'to_time' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is missing',
                'date.required' => 'date is missing',
                'from_time.required' => 'from time is missing',
//                'to_time.required' => 'to time is missing',
                'booking_uuid.required' => 'booking uuid is missing'
            ]
        ];
    }

    public function addPostLike() {
        return [
            'rules' => [
                'post_uuid' => 'required|exists:boat_posts,post_uuid',
                'user_uuid' => 'required|exists:users,user_uuid',
                'type' => 'required',
            ],
            'messages' => [
                'post_uuid.required' => 'Post uuid is missing',
                'user_uuid.required' => 'User uuid is missing',
                'type.required' => 'Favorite Type(favorite/unfavorite) is missing',
            ]
        ];
    }

    public function reportPost() {
        return [
            'rules' => [
                'post_uuid' => 'required',
                'user_uuid' => 'required',
                'comments' => 'required',
            ],
            'messages' => [
                'post_uuid.required' => 'Post uuid is missing',
                'user_uuid.required' => 'User uuid is missing',
                'comments.required' => 'Comment is missing'
            ]
        ];
    }

    public function removeBoatImage() {
        return [
            'rules' => [
                'image_uuid' => 'required'
            ],
            'messages' => [
                'image_uuid.required' => 'boat image uuid is missing'
            ]
        ];
    }

    public function resetPassword() {
        return [
            'rules' => [
                'old_password' => 'required',
                'new_password' => 'required'
            ],
            'messages' => [
                'old_password.required' => 'old password is required',
                'new_password.required' => 'new password is required'
            ]
        ];
    }

    public function forgetPassword() {
        return [
            'rules' => [
                'phone_number' => 'required',
//                'email' => 'required',
            ],
            'messages' => [
                'phone_number.required' => 'phone number is required',
                'email.required' => 'email is required',
            ]
        ];
    }

    public function setUserLanguage() {
        return [
            'rules' => [
                'user_uuid' => 'required',
                'language' => 'required'
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is missing',
                'language.required' => 'language is missing',
            ]
        ];
    }

    public function saveSupportQuestion(){
        return [
            'rules' => [
                'user_uuid' => 'required',
                'type' => 'required',
                'description' => 'required',
            ],
            'messages' => [
                'user_uuid.required' => 'user uuid is missing',
                'type.required' => 'Assistance Type is missing',
                'description.required' => 'description is missing'
            ]
        ];
    }
}
