<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatWorkingHoursRepository;
use Illuminate\Http\Request;

class BoatWorkingHoursController extends Controller
{
    protected $response = "";
    protected $boatWorkingHoursRepository = "";
    public function __construct(ApiResponse $response,BoatWorkingHoursRepository $BoatWorkingHoursRepository){
        $this->response = $response;
        $this->boatWorkingHoursRepository = $BoatWorkingHoursRepository;
    }
    public function addBoatWorkingHours(Request $request){
        return  $this->response->respond(["data"=>[
            'schedules'=>$this->boatWorkingHoursRepository->addBoatWorkingHours($request->all())
        ]]);
    }

    public function boatSchedules(Request $request){

        return  $this->response->respond(["data"=>[
            'schedules'=>$this->boatWorkingHoursRepository->boatSchedules($request->all())
        ]]);
    }

    public function multiSchedules(Request $request){
        return  $this->response->respond(["data"=>[
            'schedules'=>$this->boatWorkingHoursRepository->multiSchedules($request->all())
        ]]);
    }
}