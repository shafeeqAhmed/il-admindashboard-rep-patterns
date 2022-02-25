<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatType extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected function getTypeWithUUID($col, $val) {
        $result = BoatType::where($col, $val)->first();
        return !empty($result) ? $result->toArray() : [];
    }

}
