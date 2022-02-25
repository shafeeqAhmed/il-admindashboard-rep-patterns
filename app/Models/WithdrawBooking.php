<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WithdrawBooking extends Model
{
    use HasFactory;
    protected $table = 'withdraw_bookings';
    protected $guarded = ['id'];
    protected $fillable = ['withdraw_booking_uuid', 'booking_id', 'withdraw_id', 'amount', 'created_at', 'updated_at'];

    public function withdrawBooking(){
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function getOwnerTransferredPaymentDetail($withdraw_id)
    {
        DB::beginTransaction();
        $query = $this->with('withdrawBooking.user')->where('withdraw_id', $withdraw_id)->orderByDesc('id')->get();
        $query ? DB::commit() : DB::rollBack();
        return ($query) ? $query->toArray() : null;
    }
}
