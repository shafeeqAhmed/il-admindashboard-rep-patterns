<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Boat;

class NotificationController extends Controller
{
    protected $response = "";
    protected $notificationRepository = "";
    public function __construct(ApiResponse $response,NotificationRepository $NotificationRepository){
        $this->response = $response;
        $this->notificationRepository = $NotificationRepository;
    }
    public function create(Request $request){

        try{
            return  $this->response->respond(["data"=>[

            ]]);
        }catch (Exception $ex){
            return ExceptionHelper::returnAndSaveExceptions($ex, $request);
        }
    }


    public function getNotifications(Request $request){
        if($request->get('user_uuid')){
            $param['type'] = 'user';
            $param['id'] = User::where('user_uuid', $request->get('user_uuid'))->value('id');
        } else if($request->get('boat_uuid')){
            $param['type'] = 'boat';
            $param['id'] = Boat::where('boat_uuid', $request->get('boat_uuid'))->value('id');
        }

        return $this->response->respond(['data'=>[
            "notifications"=>$this->notificationRepository->getNotifications($param['id'], $param['type'])
        ]]);
    }
}
