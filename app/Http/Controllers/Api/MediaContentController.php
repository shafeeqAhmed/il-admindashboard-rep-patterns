<?php

namespace App\Http\Controllers\Api;

use App\Helper\ExceptionHelper;
use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatPostRepository;
use App\Repositories\BoatStoryRepository;
use App\Repositories\ReportedPostRepository;
use Illuminate\Http\Request;

class MediaContentController extends Controller
{
    protected $response = "";
    protected $boatStoryRepository = "";
    protected $boatPostRepository = "";
    protected $reportedPostRepository = "";
    public function __construct(
        ApiResponse $response,
        BoatStoryRepository $BoatStoryRepository,
        ReportedPostRepository $ReportedPostRepository,
        BoatPostRepository $BoatPostRepository
    ){
        $this->response = $response;
        $this->boatStoryRepository = $BoatStoryRepository;
        $this->boatPostRepository = $BoatPostRepository;
        $this->reportedPostRepository = $ReportedPostRepository;
    }

    public function create(Request $request){
        if($request->type == 'story'){
            return  $this->response->respond(["data"=>[
                'story'=> $this->boatStoryRepository->createBoatStory($request->all())
            ]]);
        }
        if($request->type == 'post'){
            return  $this->response->respond(["data"=>[
                'post'=> $this->boatPostRepository->createBoatPost($request->all())
            ]]);
        }

    }

    public function getPostDetail(Request $request){
        return  $this->response->respond(["data"=>[
            'post'=> $this->boatPostRepository->getPostDetail($request->all())
        ]]);
    }


    public function removeContent(Request $request){
        if($request->type == 'story'){
            $this->boatStoryRepository->removeBoatStory($request->all());
            return  $this->response->respond([]);
        }
        if($request->type == 'post'){
            $this->boatPostRepository->removeBoatPost($request->all());
            return  $this->response->respond([]);
        }
    }

    public function reportPost(Request $request)
    {
        return $this->reportedPostRepository->reportPost($request->all());
        // return  $this->response->respond([]);
    }

}
