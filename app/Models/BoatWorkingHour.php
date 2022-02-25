<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatWorkingHour extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getSchedules($boatId){
        return BoatWorkingHour::where('boat_id',$boatId)->where('is_active',1)->get()->toArray();
    }

    public function getBoatWorkingHoursByDay($day,$boat_id){
       return BoatWorkingHour::where('boat_id',$boat_id)->where('day',$day)->first();
    }
}
