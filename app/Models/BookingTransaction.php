<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTransaction extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected function createBookingTransaction($params) {
        $result = BookingTransaction::create($params);
        return ($result) ? $result->toArray() : null;
    }

    protected function updateBookingTransaction($col, $val, $data) {
        $result = BookingTransaction::where($col, $val)->update($data);
        return ($result) ? true : false;
    }

}
