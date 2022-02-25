<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatService extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function deleteServices($params){
        return self::where('boat_service_uuid',$params['service_uuid'])->update(['is_active'=>0]);
    }
}
