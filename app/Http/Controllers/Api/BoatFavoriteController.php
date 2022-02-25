<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatFavoriteRepository;
use Illuminate\Http\Request;

class BoatFavoriteController extends Controller
{
    protected $response = "";
    protected $boatFavoriteRepository = "";
    public function __construct(ApiResponse $response,BoatFavoriteRepository $BoatFavoriteRepository){
        $this->response = $response;
        $this->boatFavoriteRepository = $BoatFavoriteRepository;
    }
    public function addBoatFavorite(Request $request){
        $data = $this->boatFavoriteRepository->addBoatFavorite($request->all());
            return  $this->response->respond(["data"=>[
                'message'=>$data['message'],
                'status'=>$data['status'],
            ]]);

    }
}
