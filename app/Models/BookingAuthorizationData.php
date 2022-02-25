<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAuthorizationData extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected function saveData($data) {
        $result = BookingAuthorizationData::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getBookingAuthData($col, $val) {
        $result = BookingAuthorizationData::where($col, '=', $val)->where('is_active', '=', 1)->first();
        return !empty($result) ? $result->toArray() : [];
    }

}
