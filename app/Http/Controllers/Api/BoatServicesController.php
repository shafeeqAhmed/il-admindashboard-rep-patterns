<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatServicesRepository;
use Illuminate\Http\Request;

class BoatServicesController extends Controller
{

    protected $response = "";
    protected $boatServicesRepository = "";
    public function __construct(ApiResponse $response,BoatServicesRepository $BoatServicesRepository){
        $this->response = $response;
        $this->boatServicesRepository = $BoatServicesRepository;
    }
    public function create(Request $request){

            return  $this->response->respond(["data"=>[
               'boat'=>$this->boatServicesRepository->createServices($request->all())
            ]]);

    }

    public function deleteServices(Request $request){
        return  $this->response->respond(["data"=>[
            'boat'=>$this->boatServicesRepository->deleteServices($request->all())
        ]]);
    }


    public function getBoatService(Request $request){
        return  $this->response->respond(["data"=>$this->boatServicesRepository->getServices($request->all())
        ]);
    }
}
