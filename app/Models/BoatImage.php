<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatImage extends Model
{
    use HasFactory;

    protected $guareded = ['id'];

    protected $fillable = ['boat_image_uuid', 'url', 'boat_id'];

    public function boat(){
        return $this->belongsTo(Boat::class,'boat_id','id');
    }

    public function updateImage($col, $val, $data){
        return $this->where($col, $val)->update($data);
    }
}
