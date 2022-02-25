<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingCreatedEvent;
use App\Events\BookingRescheduleEvent;
use App\Events\BookingStatusChange;
use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Repositories\PromoCodeRepository;
use App\Traits\CommonHelper;
use App\Traits\MediaUploadHelper;
use App\Traits\Responses\BookingResponse;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{

    use BookingResponse;

    protected $response = "";
    protected $promoCodeRepository = "";
    protected $bookingRepository = "";
    public function __construct(ApiResponse $response,PromoCodeRepository $PromoCodeRepository, BookingRepository $bookingRepository){
        $this->response = $response;
        $this->promoCodeRepository = $PromoCodeRepository;
        $this->bookingRepository = $bookingRepository;
    }
    public function addPromoCode(Request $request){
        return  $this->response->respond(["data"=>[
            'promoCode'=>$this->promoCodeRepository->createPromocode($request->all())
        ]]);
    }

    public function getPromoCodes(Request $request){
        return  $this->response->respond(["data"=>[
            'promoCodes'=>$this->promoCodeRepository->getPromocodes($request->all())
        ]]);
    }

    public function removePromocode(Request $request){
        return  $this->response->respond(["data"=>[
            'promoCode'=>$this->promoCodeRepository->deletePromocode($request->all())
        ]]);
    }

    public function getPromocodeDetail(Request $request){
        return  $this->response->respond(["data"=>[
            'promoCode'=>$this->promoCodeRepository->getPromocodeDetail($request->all())
        ]]);
    }

    public function getPromoCodesByStatus(Request $request){
        return  $this->response->respond(["data"=>[
            'promoCodes'=>$this->promoCodeRepository->getPromoCodesByStatus($request->all())
        ]]);
    }

    public function testEvent(Request $request){
//        $uploadedVideo = CommonHelper::uploadSingleImage($request->video, 'mobileUploads');
//        dd($uploadedVideo);
//        $thumbnailsLambda = processThumbnails('61f2686110df41643276385.mp4', 'post_video');
//        dd($thumbnailsLambda);
//
////        moving video to post videos
//        $d = MediaUploadHelper::moveSingleS3Videos('61f2686110df41643276385.mp4', CommonHelper::$s3_image_paths['post_video']);
//        dd($d);
        $booking = Booking::with('user', 'boat.user')->first()->toArray();
        $booking['login_user_type'] = 'customer';
        event(new BookingCreatedEvent($booking));
//        event(new BookingRescheduleEvent($booking));
//        event(new BookingStatusChange($booking));
    }

    public function checkPromocodeValidity(Request $request){
        $response = $this->promoCodeRepository->checkValidity($request->all());
        if($response == false){
            return  $this->response->respond(["data"=>[
                'message'=>'invalid coupon code'
            ]]);
        }else{
            return  $this->response->respond(["data"=>[
                'promoCode'=>$response
            ]]);
        }

    }
}
