<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatFavoriteRepository;
use App\Repositories\PostLikeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostLikeController extends Controller
{
    protected $response = "";
    protected $postLikeRepository = "";
    public function __construct(ApiResponse $response,PostLikeRepository $PostLikeRepository){
        $this->response = $response;
        $this->postLikeRepository = $PostLikeRepository;
    }
    public function addPostLike(Request $request){
        return  $this->response->respond(["data"=>[
            'post'=>$this->postLikeRepository->addPostLike($request->all())
        ]]);

    }

    public function getPostLikes(Request $request){

        $validator = Validator::make($request->all(), [
            'post_uuid' => 'required',
            'limit' => 'required | integer',
            'offset' => 'required | integer',
        ]);

        if ($validator->fails()) {
            return  $this->response->respondValidation(["success"=>false, "message" => $validator->messages()->first()]);
        }

        return  $this->response->respond(["data"=>[
            'likes'=>$this->postLikeRepository->getPostLikes($request->all())
        ]]);
    }
}
