<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected function updateOrCreateData($col, $val, $data) {
        $result = BankDetail::updateOrCreate([$col => $val, 'is_active' => 1], $data);
        return $result ? $result->toArray() : [];
    }

    protected function getDetail($col, $val) {
        $result = BankDetail::where($col, $val)->where('is_active', 1)->first();
        return $result ? $result->toArray() : [];
    }

}
