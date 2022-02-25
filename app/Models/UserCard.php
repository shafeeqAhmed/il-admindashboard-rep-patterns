<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    protected function saveData($data) {
        $result = UserCard::create($data);
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getCardDetail($col, $val) {
        $result = UserCard::where($col, $val)
//                ->where('is_active', 1)
                ->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function updateData($col, $val, $data) {
        $result = true;
        if (UserCard::where($col, $val)->where('is_active', '=', 1)->exists()) {
            $result = UserCard::where($col, $val)->where('is_active', '=', 1)->update($data);
        }
        return ($result) ? true : false;
    }

    protected function getUserCards($userId) {
        $result = UserCard::where('user_id', $userId)
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->get();
        return !empty($result) ? $result->toArray() : [];
    }

}
