<?php

namespace App\Repositories;


use App\Models\NotificationSetting;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\NotificationSettingResponse;
use Illuminate\Http\Request;

//use Your Model

/**
 * Class BoatRepository.
 */
class NotificationSettingRepository extends BaseRepository implements RepositoryInterface
{
 use NotificationSettingResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return NotificationSetting::class;
    }
    public function userNotificationSettings($params) {
        $input = $this->mapOnTable($params);

        if(!$this->model->isExist($input['user_id'])) {
            $data = $this->create($this->mapOnTable($params));
        } else {
            if($params['user_type'] == 'boat'){
                $this->model->updateNotificationSetting('user_id',$input['user_id'],$this->mapOnTableForUpdateOwner($params));
            } else {
                $this->model->updateNotificationSetting('user_id',$input['user_id'],$this->mapOnTableForUpdateCustomer($params));
            }
            $data = $this->getByColumn($input['user_id'],'user_id');
        }
        return $this->notificationSettingResponse($data, $params['user_type']);
    }

    public function getNotificationSettings($params){
        $userId = (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id;
        $settings = $this->getByColumn($userId,'user_id');
        if(!$settings){
            $settings = $this->create($this->mapOnTable($params));
        }
        return $this->notificationSettingResponse($settings, $params['user_type']);
    }

    public function mapOnTable($params){

        return [
            'notification_settings_uuid' => Str::uuid()->toString(),
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
            'is_boat_blocked' => $params['is_boat_booked'] ?? 0,
            'is_payment_received' => $params['is_payment_received'] ?? 0,
            'is_booking_cancelled' => $params['is_booking_cancelled'] ?? 0,
            'is_email_on_boat_blocked' => $params['is_email_on_boat_booked'] ?? 0,
            'is_email_on_booking_cancelled' => $params['is_email_on_booking_cancelled'] ?? 0,
            'is_email_on_payment_received' => $params['is_email_on_payment_received'] ?? 0,
            'is_confirmed_customer' => $params['is_confirmed_customer'] ?? 0,
            'is_rescheduled_customer' => $params['is_rescheduled_customer'] ?? 0,
            'is_cancelled_customer' => $params['is_cancelled_customer'] ?? 0,
            'is_confirmed_email_customer' => $params['is_confirmed_email_customer'] ?? 0,
            'is_rescheduled_email_customer' => $params['is_rescheduled_email_customer'] ?? 0,
            'is_cancelled_email_customer' => $params['is_cancelled_email_customer'] ?? 0,
        ];
    }
    public function mapOnTableForUpdateOwner($params){

        return [
            'is_boat_blocked' => $params['is_boat_booked'] ?? 1,
            'is_payment_received' => $params['is_payment_received'] ?? 1,
            'is_booking_cancelled' => $params['is_booking_cancelled'] ?? 1,
            'is_email_on_boat_blocked' => $params['is_email_on_boat_booked'] ?? 0,
            'is_email_on_booking_cancelled' => $params['is_email_on_booking_cancelled'] ?? 0,
            'is_email_on_payment_received' => $params['is_email_on_payment_received'] ?? 0,
        ];
    }

    public function mapOnTableForUpdateCustomer($params){

        return [
            'is_confirmed_customer' => $params['is_confirmed_customer'] ?? 1,
            'is_rescheduled_customer' => $params['is_rescheduled_customer'] ?? 1,
            'is_cancelled_customer' => $params['is_cancelled_customer'] ?? 1,
            'is_confirmed_email_customer' => $params['is_confirmed_email_customer'] ?? 0,
            'is_rescheduled_email_customer' => $params['is_rescheduled_email_customer'] ?? 0,
            'is_cancelled_email_customer' => $params['is_cancelled_email_customer'] ?? 0,
        ];
    }

}
