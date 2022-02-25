<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getPromocode($col, $val){
        return $this->where($col, $val)->where('is_active', 1)->first();
    }

    public function getPromocodes($col, $val){
        return $this->where($col, $val)->where('is_active', 1)->get();
    }

    public function checkValidity($params){
        $user_id = User::where('user_uuid',$params['user_uuid'])->value('id');
        $boat_id = Boat::where('boat_uuid',$params['boat_uuid'])->value('id');
        $code = self::where('boat_id',$boat_id)->where('coupon_code',$params['code'])->first();

        if($code !=null){
            $codeInBooking = Booking::where('user_id',$user_id)->where('boat_id',$boat_id)->where('promo_code_id',$code->id)->exists();

            if(!$codeInBooking){
                return $code;
            }else{
                return null;
            }
        }else{
            return null;
        }



    }

}
