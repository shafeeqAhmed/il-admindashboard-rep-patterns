<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\StoryRepository;
use Illuminate\Http\Request;

class StoryController extends Controller {

    protected $response = "";
    protected $storyRepository = "";

    public function __construct(ApiResponse $response, StoryRepository $StoryRepository) {
        $this->response = $response;
        $this->storyRepository = $StoryRepository;
    }

    public function getStories(Request $request) {
        return $this->response->respond(["data" => [
                        'boat_stories' => $this->storyRepository->getStories($request->all())
        ]]);
    }

}
