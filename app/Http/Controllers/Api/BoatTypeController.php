<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatTypeRepository;
use Illuminate\Http\Request;

class BoatTypeController extends Controller {

    protected $response = "";
    protected $boatTypeRepository = "";

    public function __construct(ApiResponse $response, BoatTypeRepository $BoatTypeRepository) {
        $this->response = $response;
        $this->boatTypeRepository = $BoatTypeRepository;
    }

    public function getBoatTypes(Request $request) {

        return $this->response->respond(["data" => [
                        'boat_types' => $this->boatTypeRepository->getBoatTypes($request->all())
        ]]);
    }

}
