<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatStoriesViewedRepository;
use Illuminate\Http\Request;

class BoatStoriesViewedController extends Controller
{
    protected $response = "";
    protected $boatStoriesViewedRepository = "";
    public function __construct(ApiResponse $response,BoatStoriesViewedRepository $BoatStoriesViewedRepository){
        $this->response = $response;
        $this->boatStoriesViewedRepository = $BoatStoriesViewedRepository;
    }
    public function addStoryView(Request $request){
        $this->boatStoriesViewedRepository->addStoryView($request->all());
        return $this->response->respond([]);
    }
}
