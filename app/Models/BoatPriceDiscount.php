<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatPriceDiscount extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function deleteRecordsById($id){
        return BoatPriceDiscount::where('boat_id',$id)->where('is_active',1)->update(['is_active'=>0]);
    }


}
