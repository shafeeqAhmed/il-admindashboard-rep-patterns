<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class,'sender_id','id');
    }

    public function boat(){
        return $this->belongsTo(Boat::class,'sender_id','id');
    }

    public function getNotifications($id, $type){

            $query = self::query();
            $query->where('receiver_id', $id);
            $query->where('receiver_type', $type);
            $query->where('is_active', 1);
            $query->with('notificationModel');
            $query->when($type == 'boat', function($q){
               $q->with('user');
            });
            $query->when($type == 'user', function($q){
               $q->with('boat.boatType');
            });
            $records = $query->get();
            return ($records)?$records->toArray():null;
    }

    public function notificationModel()
    {
        return $this->morphTo();
    }
}
