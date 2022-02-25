<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatImagesRepository;
use Illuminate\Http\Request;

class BoatImagesController extends Controller
{
    protected $response = "";
    protected $boatImagesRepository = "";
    public function __construct(ApiResponse $response,BoatImagesRepository $BoatImagesRepository){
        $this->response = $response;
        $this->boatImagesRepository = $BoatImagesRepository;
    }

    public function removeBoatImage(Request $request){
        $this->boatImagesRepository->removeImage($request->all());
        return $this->response->respond([]);
    }
}
