<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatDocument extends Model {

    use HasFactory;

    protected $guarded = ['id'];


    public function required_documents(){
        return $this->hasOne(BoatRequiredDocument::class, 'id', 'boat_required_document_id');
    }

    public function updateDocument($col, $val, $data){
        return $this->where($col, $val)->update($data);
    }
}
