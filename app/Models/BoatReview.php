<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatReview extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function getBoatRating($boatId){
       return BoatReview::where('boat_id',$boatId)->avg('rating');
    }
    public function isBoatReviewExist($boat_id,$booking_id,$user_id) {
        return self::where(['boat_id'=>$boat_id,'booking_id'=>$booking_id,'user_id'=>$user_id])->exists();
    }
    public function isReplied($review_uuid) {
        return self::where('review_uuid',$review_uuid)->whereNotNull('reply')->exists();
    }
    public function createBoatReviewReply($column,$value,$data){
        return self::where($column,$value)->update($data);
    }
    public function user() {
       return $this->belongsTo(User::class,'user_id','id');
    }
}
