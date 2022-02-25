<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatReviewRepository;
use Illuminate\Http\Request;

class BoatReviewController extends Controller
{
    protected $response = "";
    protected $boatReviewRepository = "";
    public function __construct(ApiResponse $response,BoatReviewRepository $BoatReviewRepository){
        $this->response = $response;
        $this->boatReviewRepository = $BoatReviewRepository;
    }
    public function createBoatReview(Request $request){

        return  $this->response->respond(["data"=>[
            'boat_review'=>$this->boatReviewRepository->createBoatReview($request->all())
        ]]);
    }
    public function createBoatReviewReply(Request $request){

        return  $this->response->respond(["data"=>[
            'boat_review'=>$this->boatReviewRepository->createBoatReviewReply($request->all())
        ]]);
    }
}
