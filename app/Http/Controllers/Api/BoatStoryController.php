<?php

namespace App\Http\Controllers\Api;

use App\Helper\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatStoryRepository;
use Illuminate\Http\Request;

class BoatStoryController extends Controller
{
    protected $response = "";
    protected $boatStoryRepository = "";

    public function __construct(
        ApiResponse $response,
        BoatStoryRepository $BoatStoryRepository

    ){
        $this->response = $response;
        $this->boatStoryRepository = $BoatStoryRepository;

    }
    public function create(Request $request){
        return  $this->response->respond(["data"=>[
            'story'=> $this->boatStoryRepository->createBoatStory($request->all())
        ]]);
    }

    public function getBoatStories(Request $request){
        return  $this->response->respond(["data"=>[
            'stories'=> $this->boatStoryRepository->getBoatStories($request->all())
        ]]);
    }

    public function getSingleStory(Request $request){
        return $this->response->respond([
            "data"=> $this->boatStoryRepository->getSingleStory($request->all())
        ]);
    }

}
