<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatRequiredDocument extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function getDocuments(){
        $data = $this->where('is_active', 1)->get();
        return $data ? $data->toArray() : [];
    }

    public function getDocument($boat_id)
    {
        return $this->belongsTo('App\Models\BoatDocument','id','boat_required_document_id')->where('boat_id',$boat_id);
    }
}
