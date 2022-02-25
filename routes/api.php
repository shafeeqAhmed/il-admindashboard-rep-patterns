<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
//***************************************** User Sign Up ********************************
//Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}'], 'middleware' => 'setlocale'], function() {

Route::post('/user-signup',
        [
            'middleware' =>
            [
                'ApiValidator:userSignUp'
            ],
            'uses' => 'UserController@signUp', 'as' => 'user-signup'
        ]
);
Route::get('/get-user-personal-information',
        [
            'middleware' =>
            [
                'ApiValidator:getUserPersonalInformation'
            ],
            'uses' => 'UserController@getUserPersonalInformation', 'as' => 'getUserPersonalInformation'
        ]
);
Route::post('/update-user',
        [
            'middleware' =>
            [
                'ApiValidator:updateUser'
            ],
            'uses' => 'UserController@updateUser', 'as' => 'updateUser'
        ]
);

Route::post('/update-user-image',
        [
            'middleware' =>
            [
                'ApiValidator:updateUserImage'
            ],
            'uses' => 'UserController@updateUserImage', 'as' => 'updateUserImage'
        ]
);
Route::get('/get-countries',
        [
            'uses' => 'UserController@getCountries', 'as' => 'getCountries'
        ]
);
Route::post('/set-user-language',
        [
            'middleware' =>
            [
                'ApiValidator:setUserLanguage'
            ],
            'uses' => 'UserController@setUserLanguage', 'as' => 'setUserLanguage'
        ]
);
Route::post('/save-customer-support',
        [
            'middleware' =>
            [
                'ApiValidator:saveSupportQuestion'
            ],
            'uses' => 'UserController@saveCustomerSupport', 'as' => 'saveCustomerSupport'
        ]
);
// ******************************************  Login *************************************
Route::post('/login',
        [
            'middleware' =>
            [
                'ApiValidator:loginRequest'
            ],
            'uses' => 'LoginController@login', 'as' => 'login'
        ]
);

Route::post('/logout',
        [
            'middleware' =>
            [
                'ApiValidator:logoutRequest'
            ],
            'uses' => 'LoginController@logout', 'as' => 'logout'
        ]
);
Route::get('/get-all-user',
        [
            'middleware' =>
            [
//              'ApiValidator:loginRequest'
            ],
            'uses' => 'LoginController@index', 'as' => 'get-all-user'
        ]
);

//*****************************************************************************************
//******************************************  Booking *************************************

Route::get('/create-booking',
        [
            'middleware' =>
            [
            //  'ApiValidator:loginRequest'
            ],
            'uses' => 'BookingController@index', 'as' => 'create-booking'
        ]
);

Route::get('/get-booking-detail',
        [
            'middleware' =>
            [
                'ApiValidator:getBookingDetail'
            ],
            'uses' => 'BookingController@getBookingDetail', 'as' => 'get-booking-detail'
        ]
);

Route::post('/update-booking',
        [
            'middleware' =>
            [
                'ApiValidator:updateBooking'
            ],
            'uses' => 'BookingController@updateBookingRequest', 'as' => 'updateBookingRequest'
        ]
);
Route::post('/update-status',
        [
            'middleware' =>
            [
                'ApiValidator:updateStatus'
            ],
            'uses' => 'BookingController@updateStatus', 'as' => 'updateStatus'
        ]
);

Route::post('/reschedule-booking',
        [
            'middleware' =>
            [
                'ApiValidator:rescheduleBooking'
            ],
            'uses' => 'BookingController@rescheduleBooking', 'as' => 'reschedule-booking'
        ]
);
//Route::post('/leave-review',
//    [
//        'middleware' =>
//            [
//                'ApiValidator:LeaveReview'
//            ],
//        'uses' => 'BookingController@LeaveReview', 'as' => 'leave-review'
//    ]
//);
//************************************************************************************
//******************************************  Boat *************************************


Route::get('/filter-boats',
        [
            'middleware' =>
            [
//                'ApiValidator:filterBoats'
            ],
            'uses' => 'BoatController@filterBoats', 'as' => 'filter-boats'
        ]
);

Route::post('/create-boat',
        [
            'middleware' =>
            [
                'ApiValidator:createBoat'
            ],
            'uses' => 'BoatController@create', 'as' => 'create-boat'
        ]
);

Route::get('/get-boat-services',
        [
            'middleware' =>
            [
//                'ApiValidator:addServices'
            ],
            'uses' => 'BoatServicesController@getBoatService', 'as' => 'get-boat-services'
        ]
);

Route::post('/create-boat-services',
        [
            'middleware' =>
            [
                'ApiValidator:addServices'
            ],
            'uses' => 'BoatServicesController@create', 'as' => 'create-boat-services'
        ]
);

Route::delete('/remove-boat',
        [
            'middleware' =>
            [
                'ApiValidator:removeBoat'
            ],
            'uses' => 'BoatController@removeBoat', 'as' => 'remove-boat'
        ]
);

Route::delete('/delete-service',
        [
            'middleware' =>
            [
                'ApiValidator:deleteServices'
            ],
            'uses' => 'BoatServicesController@deleteServices', 'as' => 'delete-service'
        ]
);

Route::post('/update-boat-location',
        [
            'middleware' =>
            [
                'ApiValidator:updateBoatLocation'
            ],
            'uses' => 'BoatController@updateBoatLocation', 'as' => 'update-boat-location'
        ]
);

Route::post('/add-captain',
        [
            'middleware' =>
            [
                'ApiValidator:addCaptain'
            ],
            'uses' => 'BoatController@addCaptain', 'as' => 'add-captain'
        ]
);

Route::delete('/delete-captain',
        [
            'middleware' =>
            [
                'ApiValidator:deleteCaptain'
            ],
            'uses' => 'BoatController@deleteCaptain', 'as' => 'delete-captain'
        ]
);

Route::post('/update-captain',
        [
            'middleware' =>
            [
                'ApiValidator:updateCaptain'
            ],
            'uses' => 'BoatController@updateCaptain', 'as' => 'update-captain'
        ]
);

Route::post('/add-boat-price',
        [
            'middleware' =>
            [
                'ApiValidator:addBoatPrice'
            ],
            'uses' => 'BoatController@addBoatPrice', 'as' => 'add-boat-price'
        ]
);

Route::get('/get-user-boats',
        [
            'middleware' =>
            [
                'ApiValidator:getUserBoats'
            ],
            'uses' => 'UserController@getUserBoats', 'as' => 'get-user-boats'
        ]
);

Route::get('/get-boat-detail',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatDetail'
            ],
            'uses' => 'BoatController@getBoatDetail', 'as' => 'get-boat-detail'
        ]
);

Route::post('/add-boat-working-hours',
        [
            'middleware' =>
            [
                'ApiValidator:addBoatWorkingHours'
            ],
            'uses' => 'BoatWorkingHoursController@addBoatWorkingHours', 'as' => 'add-boat-working-hours'
        ]
);
Route::post('/add-boat-favorite',
        [
            'middleware' =>
            [
                'ApiValidator:addBoatFavorite'
            ],
            'uses' => 'BoatFavoriteController@addBoatFavorite', 'as' => 'add-boat-favorite'
        ]
);

Route::post('/book-boat',
        [
            'middleware' =>
            [
                'ApiValidator:bookBoat'
            ],
            'uses' => 'BoatController@bookBoat', 'as' => 'book-boat'
        ]
);

Route::get('/get-boat-calendar',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatCalender'
            ],
            'uses' => 'BoatController@getBoatCalender', 'as' => 'get-boat-calender'
        ]
);

Route::get('/get-calendar-bookings',
        [
            'middleware' =>
            [
                'ApiValidator:getCalendarBookings'
            ],
            'uses' => 'BoatController@getCalendarBookings', 'as' => 'get-calendar-bookings'
        ]
);

Route::get('/get-boat-dashboard',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatDashboard'
            ],
            'uses' => 'BoatController@getBoatDashboard', 'as' => 'get-boat-dashboard'
        ]
);

Route::get('/get-boat-bookings',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatBookings'
            ],
            'uses' => 'BoatController@getBoatBookings', 'as' => 'get-boat-bookings'
        ]
);

Route::get('/get-boat-settings',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatSettings'
            ],
            'uses' => 'BoatController@getBoatSettings', 'as' => 'get-boat-settings'
        ]
);

Route::delete('/remove-boat-image',
        [
            'middleware' =>
            [
                'ApiValidator:removeBoatImage'
            ],
            'uses' => 'BoatImagesController@removeBoatImage', 'as' => 'remove-boat-image'
        ]
);

Route::delete('/remove-boat-document',
        [
            'middleware' =>
            [
                'ApiValidator:removeBoatDocument'
            ],
            'uses' => 'BoatDocumentController@removeBoatDocument', 'as' => 'remove-boat-document'
        ]
);

//************************************************************************************
//****************************************** Customer *****************************************




Route::get('/get-customer-dashboard-counts',
        [
            'middleware' =>
            [
                'ApiValidator:getCustomerDashboard'
            ],
            'uses' => 'CustomerController@getCustomerDashboardCount', 'as' => 'get-customer-dashboard-counts'
        ]
);

Route::get('/get-customer-booking',
        [
            'middleware' =>
            [
                'ApiValidator:getCustomerBooking'
            ],
            'uses' => 'CustomerController@getCustomerBooking', 'as' => 'get-customer-booking'
        ]
);

//****************************************** End Customer *************************************
// ******************************************  verify code *************************************
Route::post('/verify-code',
        [
            'middleware' =>
            [
                'ApiValidator:verifyCode'
            ],
            'uses' => 'LoginController@verifyCode', 'as' => 'verifyCode'
        ]
);
// ****************************************** get verification code *************************************

Route::post('/get-code',
        [
            'middleware' =>
            [
                'ApiValidator:getCode'
            ],
            'uses' => 'UserController@getCode', 'as' => 'getCode'
        ]
);

// ****************************************** change password *************************************

Route::post('/change-password',
        [
            'middleware' =>
            [
                'ApiValidator:resetPassword'
            ],
            'uses' => 'UserController@resetPassword', 'as' => 'resetPassword'
        ]
);
//*****************************************************************************************
// TODO: Remove this reset password endpoint after build
// ****************************************** reset password *************************************

Route::post('/reset-password',
        [
            'middleware' =>
            [
                'ApiValidator:resetPassword'
            ],
            'uses' => 'UserController@resetPassword', 'as' => 'resetPassword'
        ]
);
//*****************************************************************************************
// ****************************************** forget password *************************************

Route::post('/forget-password',
        [
            'middleware' =>
            [
                'ApiValidator:forgetPassword'
            ],
            'uses' => 'UserController@forgetPassword', 'as' => 'forgetPassword'
        ]
);
//*****************************************************************************************
// ****************************************** get verification code *************************************

Route::post('/forget-password',
        [
            'middleware' =>
            [
                'ApiValidator:forgetPassword'
            ],
            'uses' => 'UserController@forgetPassword', 'as' => 'forgetPassword'
        ]
);
//*****************************************************************************************
//**************************************** Boat Schedules ****************************
Route::get('/boat-schedules',
        [
            'middleware' =>
            [
                'ApiValidator:boatSchedules'
            ],
            'uses' => 'BoatWorkingHoursController@boatSchedules', 'as' => 'boat-schedules'
        ]
);

Route::get('/boat-multi-schedules',
        [
            'middleware' =>
            [
                'ApiValidator:multiSchedules'
            ],
            'uses' => 'BoatWorkingHoursController@multiSchedules', 'as' => 'boat-schedules'
        ]
);

//************************************************************************************
//******************************************  Stories *************************************



Route::post('/create-story',
        [
            'middleware' =>
            [
                'ApiValidator:addBoatStory'
            ],
            'uses' => 'BoatStoryController@create', 'as' => 'create-story'
        ]
);

Route::get('/get-boat-stories',
        [
            'middleware' =>
            [
                'ApiValidator:getProfileStories'
            ],
            'uses' => 'BoatStoryController@getBoatStories', 'as' => 'get-bot-stories'
        ]
);

Route::get('/get-single-story',
        [
            'middleware' =>
            [
                'ApiValidator:getSingleStory'
            ],
            'uses' => 'BoatStoryController@getSingleStory', 'as' => 'get-single-story'
        ]
);

Route::post('/add-story-view',
    [
        'middleware' =>
            [
                'ApiValidator:addStoryView'
            ],
        'uses' => 'BoatStoriesViewedController@addStoryView', 'as' => 'add-story-view'
    ]
);

//************************************************************************************
//************************************************************************************
//**************************************** Get Stories for customer home****************************
Route::get('/get-home-stories',
        [
            'middleware' =>
            [
                'ApiValidator:getStories'
            ],
            'uses' => 'StoryController@getStories', 'as' => 'getStories'
        ]
);
//************************************************************************************
//**************************************** Get All Boat Types****************************
Route::get('/get-boat-types',
        [
            'uses' => 'BoatTypeController@getBoatTypes', 'as' => 'getBoatTypes'
        ]
);
//************************************************************************************
//**************************************** Get boats for customer home****************************
Route::get('/get-boats-with-type',
        [
            'middleware' =>
            [
                'ApiValidator:getBoatsWithType'
            ],
            'uses' => 'BoatController@getBoatsWithType', 'as' => 'getBoatsWithType'
        ]
);
//************************************************************************************
// ******************************************  Boat Reviews *************************************
Route::post('/create-boat-review',
        [
            'middleware' =>
            [
                'ApiValidator:createBoatReview'
            ],
            'uses' => 'BoatReviewController@createBoatReview', 'as' => 'create-boat-review'
        ]
);
Route::post('/create-boat-review-reply',
        [
            'middleware' =>
            [
                'ApiValidator:createBoatReviewReply'
            ],
            'uses' => 'BoatReviewController@createBoatReviewReply', 'as' => 'create-boat-review-reply'
        ]
);
//**************************************** Get boats for customer home****************************
Route::post('/search-map-boats',
        [
            'middleware' =>
            [
                'ApiValidator:searchMapBoats'
            ],
            'uses' => 'BoatController@searchMapBoats', 'as' => 'searchMapBoats'
        ]
);
//************************************************************************************
//**************************************** Payment Related Methods ****************************
Route::post('/get-request-token',
        [
            'middleware' =>
            [
                'ApiValidator:getRequestToken'
            ],
            'uses' => 'PaymentController@getRequestToken', 'as' => 'getRequestToken'
        ]
);

Route::post('/save-authorization-data',
        [
            'middleware' =>
            [
                'ApiValidator:saveAuthorizationData'
            ],
            'uses' => 'PaymentController@saveAuthorizationData', 'as' => 'saveAuthorizationData'
        ]
);

Route::post('/get-cards-list',
        [
            'middleware' =>
            [
                'ApiValidator:getCardsList'
            ],
            'uses' => 'PaymentController@getCardsList', 'as' => 'getCardsList'
        ]
);

Route::delete('/delete-card',
        [
            'middleware' =>
            [
                'ApiValidator:deleteCard'
            ],
            'uses' => 'PaymentController@deleteCard', 'as' => 'deleteCard'
        ]
);

Route::post('/transaction-feedback',
        [
            'middleware' =>
            [
                'ApiValidator:transactionFeedback'
            ],
            'uses' => 'PaymentController@transactionFeedback', 'as' => 'transactionFeedback'
        ]
);
Route::post('/payment-notification',
        [
            'middleware' =>
            [
                'ApiValidator:paymentNotification'
            ],
            'uses' => 'PaymentController@paymentNotification', 'as' => 'paymentNotification'
        ]
);

//************************************************************************************
//**************************************** Wallet Related APIs************************
Route::get('/get-transactions',
        [
            'middleware' =>
            [
                'ApiValidator:getTransactions'
            ],
            'uses' => 'WalletController@getTransactions', 'as' => 'getTransactions'
        ]
);
Route::get('/get-pending-transactions',
    [
        'middleware' =>
            [
                'ApiValidator:getPendingTransactions'
            ],
        'uses' => 'WalletController@getPendingTransactions', 'as' => 'getPendingTransactions'
    ]
);
Route::get('/get-transaction-detail',
    [
        'middleware' =>
            [
                'ApiValidator:getTransactionDetail'
            ],
        'uses' => 'WalletController@getTransactionDetail', 'as' => 'getTransactionDetail'
    ]
);
Route::get('/get-balance',
        [
            'middleware' =>
            [
                'ApiValidator:getBalance'
            ],
            'uses' => 'WalletController@getBalance', 'as' => 'getBalance'
        ]
);
Route::post('/add-bank-detail',
        [
            'middleware' =>
            [
                'ApiValidator:addBankDetail'
            ],
            'uses' => 'WalletController@addBankDetail', 'as' => 'addBankDetail'
        ]
);
Route::get('/get-bank-detail',
        [
            'middleware' =>
            [
                'ApiValidator:getBankDetail'
            ],
            'uses' => 'WalletController@getBankDetail', 'as' => 'getBankDetail'
        ]
);

Route::get('/transfer-balance',
    [
        'middleware' =>
            [
                'ApiValidator:transferBalance'
            ],
        'uses' => 'WithdrawController@transferBalance', 'as' => 'transferBalance'
    ]
);

Route::get('/transfer-balance-detail',
    [
        'middleware' =>
            [
                'ApiValidator:transferBalanceDetail'
            ],
        'uses' => 'WithdrawController@transferBalanceDetail', 'as' => 'transferBalanceDetail'
    ]
);
//************************************************************************************
//**************************************** PromoCodes ****************************

Route::get('/get-promocodes',
        [
            'middleware' =>
            [
                'ApiValidator:getPromoCodes'
            ],
            'uses' => 'PromoCodeController@getPromoCodes', 'as' => 'get-promocodes'
        ]
);

Route::get('/check-promocode-validity',
        [
            'middleware' =>
            [
                'ApiValidator:checkPromoCodeValidity'
            ],
            'uses' => 'PromoCodeController@checkPromocodeValidity', 'as' => 'check-promocode-validity'
        ]
);

Route::get('/get-promocodes-by-status',
        [
            'middleware' =>
            [
                'ApiValidator:getPromoCodesByStatus'
            ],
            'uses' => 'PromoCodeController@getPromoCodesByStatus', 'as' => 'get-promocodes-by-status'
        ]
);

Route::get('/get-promocode-detail',
        [
            'middleware' =>
            [
                'ApiValidator:getPromocodeDetail'
            ],
            'uses' => 'PromoCodeController@getPromocodeDetail', 'as' => 'get-promocode-detail'
        ]
);

Route::post('/create-promocode',
        [
            'middleware' =>
            [
                'ApiValidator:addPromoCode'
            ],
            'uses' => 'PromoCodeController@addPromoCode', 'as' => 'add-promocode'
        ]
);

Route::delete('/delete-promocode',
        [
            'middleware' =>
            [
                'ApiValidator:removePromocode'
            ],
            'uses' => 'PromoCodeController@removePromocode', 'as' => 'remove-promocode'
        ]
);

Route::get('/test-event',
        [
            'middleware' =>
            [
            //'ApiValidator:removePromocode'
            ],
            'uses' => 'PromoCodeController@testEvent', 'as' => 'test-event'
        ]
);

//************************************** Notifications *************************************

Route::get('/get-notifications',
        [
            'middleware' =>
            [
                'ApiValidator:getNotifications'
            ],
            'uses' => 'NotificationController@getNotifications', 'as' => 'get-notifications'
        ]
);

Route::post('/user-notification-settings',
        [
            'middleware' =>
            [
                'ApiValidator:userNotificationSettings'
            ],
            'uses' => 'NotificationSettingController@userNotificationSettings', 'as' => 'userNotificationSettings'
        ]
);

Route::get('/get-notification-settings',
    [
        'middleware' =>
            [
                'ApiValidator:getNotificationSettings'
            ],
        'uses' => 'NotificationSettingController@getNotificationSettings', 'as' => 'get-notification-settings'
    ]
);
Route::get('/get-user-notification-settings',
        [
            'middleware' =>
            [
                'ApiValidator:getUserNotificationSettings'
            ],
            'uses' => 'NotificationSettingController@getUserNotificationSettings', 'as' => 'getUserNotificationSettings'
        ]
);

Route::post('/create-content',
        [
            'middleware' =>
            [
                'ApiValidator:addContent'
            ],
            'uses' => 'MediaContentController@create', 'as' => 'create-content'
        ]
);

Route::post('/add-post-like',
        [
            'middleware' =>
            [
                'ApiValidator:addPostLike'
            ],
            'uses' => 'PostLikeController@addPostLike', 'as' => 'add-post-like'
        ]
);

Route::get('/get-post-likes',
    [
        'middleware' =>
            [
                'ApiValidator:getPostLikes'
            ],
        'uses' => 'PostLikeController@getPostLikes', 'as' => 'get-post-likes'
    ]
);

Route::get('/get-favourite-boat',
        [
            'middleware' =>
            [
                'ApiValidator:getFavouriteBoat'
            ],
            'uses' => 'BoatController@getFavouriteBoat', 'as' => 'get-favourite-boat'
        ]
);
//************************************************************************************
//************************************** Boat Documents *************************************


Route::get('/get-required-documents',
        [
            'middleware' =>
            [
                'ApiValidator:getRequireDocuments'
            ],
            'uses' => 'RequiredDocumentController@getRequiredDocuments', 'as' => 'get-required-documents'
        ]
);

//**************************************** Post *********************************************

Route::delete('/remove-content',
        [
            'middleware' =>
            [
                'ApiValidator:removeContent'
            ],
            'uses' => 'MediaContentController@removeContent', 'as' => 'remove-content'
        ]
);

Route::get('/get-post-detail',
        [
            'middleware' =>
            [
                'ApiValidator:getPostDetail'
            ],
            'uses' => 'MediaContentController@getPostDetail', 'as' => 'get-post-detail'
        ]
);

Route::post('/report-post',
        [
            'middleware' =>
            [
                'ApiValidator:reportPost'
            ],
            'uses' => 'MediaContentController@reportPost', 'as' => 'report-post'
        ]
);

Route::get('/send-mail',
        [
            'uses' => 'UserController@sendMailUser', 'as' => 'send-mail'
        ]
);

//});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('install-app', function(){
    return view('install_app');
});
