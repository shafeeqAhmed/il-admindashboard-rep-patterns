<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\NotificationSettingRepository;
use Illuminate\Http\Request;
use App\Traits\Responses\NotificationSettingResponse;

class NotificationSettingController extends Controller
{
    use NotificationSettingResponse;
    protected $response = "";
    protected $notificationSettingRepository = "";
    public function __construct(ApiResponse $response,NotificationSettingRepository $NotificationSettingRepository){
        $this->response = $response;
        $this->notificationSettingRepository = $NotificationSettingRepository;
    }
    public function userNotificationSettings(Request $request){
        return  $this->response->respond(["data"=>[
            'notification_settings'=>$this->notificationSettingRepository->userNotificationSettings($request->all())
        ]]);
    }

    public function getNotificationSettings(Request $request){
        return  $this->response->respond(["data"=>[
            'notification_settings'=>$this->notificationSettingRepository->getNotificationSettings($request->all())
        ]]);
    }
}
