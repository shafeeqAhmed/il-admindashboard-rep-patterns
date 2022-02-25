<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Withdraw extends Model
{
    use HasFactory;

    protected $table = 'withdraws';
    protected $guarded = ['id'];
    protected $fillable = ['withdraw_uuid', 'user_id', 'amount', 'amount', 'receipt_date', 'last_withdraw_date', 'receipt_url', 'receipt_id', 'created_at', 'updated_at'];

    public function singleWithdrawBooking()
    {
        return $this->hasOne(WithdrawBooking::class, 'withdraw_id', 'id');
    }

    public function addBoatBookingWithdraw($data)
    {
        DB::beginTransaction();
        $query = $this->create($data);
        $query ? DB::commit() : DB::rollBack();
        return ($query) ? $query->toArray() : null;
    }

    public function withdrawBookings()
    {
        return $this->hasMany(WithdrawBooking::class, 'withdraw_id', 'id');
    }

    public function withdrawUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getOwnerTransferredPayments($user_uuid)
    {
        DB::beginTransaction();
        $query = $this->where('user_id', $user_uuid)->orderByDesc('id')->get();
        $query ? DB::commit() : DB::rollBack();
        return ($query) ? $query->toArray() : null;
    }

    public function getLatesOwnerBooking($user_id){
        $resp = $this->where('user_id', $user_id)->with('singleWithdrawBooking')->first();
        return !empty($resp) ? $resp->toArray() : [];
    }
    public function getRecords($col,$val,$params=[]){
        $query = self::where($col,$val);
        $take = isset($params['offset']) ? (($params['offset'] <= 0) ? 0 : $params['offset']) : 0;
        if(isset($params['limit'])) {
            $query->skip($take)->take($params['limit']);
        }
        $result = $query->get();
        return $result ? $result->toArray() : [];
    }
}
