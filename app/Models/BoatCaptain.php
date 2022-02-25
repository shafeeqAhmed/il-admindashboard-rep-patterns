<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatCaptain extends Model
{
    use HasFactory;

    protected $guarded =['id'];

    public function captain_user(){
        return $this->hasOne(User::class,'id','user_id')->where('is_active',1);
    }

    public function getCaptain($col, $val){
        return $this->where($col, $val)->where('is_active', 1)->with('captain_user')->first();
    }

    public function deleteCaptain($params){
        return self::where('captain_uuid',$params['captain_uuid'])->update(['is_active'=>0]);
    }

    public function deleteCaptainByBoatId($boatId){
        return self::where('boat_id',$boatId)->update(['is_active'=>0]);
    }
}
