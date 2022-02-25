<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;
    protected $guarded = ['new_appointment','cancellation','no_show','new_follower'];

    public function isExist($user_id) {
        return self::where('user_id',$user_id)->exists();
    }
    public function updateNotificationSetting($column,$value,$data)
    {
        return self::where($column,$value)->update($data);

    }
}
