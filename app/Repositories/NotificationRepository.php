<?php

namespace App\Repositories;


use App\Models\Notification;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\NotificationResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class NotificationRepository extends BaseRepository implements RepositoryInterface
{
 use NotificationResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Notification::class;
    }

    public function sendBookingCreatedNotification($booking){
        $record = [
            'object_id'=>$booking['id'],
            'object_type'=>'new_appointment',
            'sender_id'=>$booking['user_id'],
            'receiver_id'=>$booking['boat_id'],
            'sender_type'=>'user',
            'receiver_type'=>'boat',
            'message'=>$booking['user']['first_name'].' '.$booking['user']['last_name'] .'Book you boat'
        ];

        return $this->create($this->mapOnTable($record));
    }

    public function getNotifications($id, $type){
        return $this->makeMultipleResponse($this->model->getNotifications($id, $type));
    }


    public function makeMultipleResponse($records){

        $finalRes = [];
        foreach($records as $record){
            $finalRes[] = $this->NotificationResponse($record);
        }
        return $finalRes;
    }

    public function mapOnTable($params){
        return [
            'notification_uuid'=>Str::uuid()->toString(),
            'object_id'=>$params['object_id'],
            'object_type'=>$params['object_type'],
            'sender_id'=>$params['sender_id'],
            'receiver_id'=>$params['receiver_id'],
            'sender_type'=>$params['sender_type'],
            'receiver_type'=>$params['receiver_type'],
            'message'=>$params['message'],
        ];
    }

}
