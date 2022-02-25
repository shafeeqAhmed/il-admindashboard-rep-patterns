<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatFavorite extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function isBoatFavoriteExist($boat_id,$user_id) {
        return self::where(['boat_id'=>$boat_id,'user_id'=>$user_id])->exists();
    }

    public function getFavouriteBoatsCount($user_id){
        return BoatFavorite::where('user_id',$user_id)->count();
    }

    public function boat(){
        return $this->belongsTo(Boat::class,'boat_id','id');
    }

    public function getFavouriteBoatsByUser($user_id){
       $favourtie = BoatFavorite::where('user_id',$user_id)
           ->with('boat.BoatType', 'boat.BoatServices', 'boat.boat_images')
           ->get();

        return ($favourtie)?$favourtie->toArray():null;
    }
}
