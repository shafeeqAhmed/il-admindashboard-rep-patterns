<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CommonHelper;
use DB;

class Booking extends Model {

    use HasFactory;

    protected $table = 'bookings';
    protected $guarded = ['id'];

    public function reviews() {
        return $this->hasMany(BoatReview::class, 'booking_id', 'id')->where('is_active', 1);
    }

    public function singleBookingTransaction() {
        return $this->hasOne(BookingTransaction::class, 'booking_id', 'id')->where('is_active', 1)->orderBy('created_at', 'ASC');
    }

    public function refundedTransaction()
    {
        return $this->hasOne(BookingTransaction::class, 'booking_id', 'id')->where('transaction_status', 'refunded');
    }
    public function bookingTransactions() {
        return $this->hasMany(BookingTransaction::class, 'booking_id', 'id')->where('is_active', 1)->orderBy('created_at', 'DESC');
    }

    public function getBoatTotalTours($boatId) {
        return Booking::where('boat_id', $boatId)->count();
    }

    public function boat() {
        return $this->belongsTo(Boat::class, 'boat_id', 'id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function promoCode() {
        return $this->belongsTo(PromoCode::class, 'promo_code_id', 'id');
    }

    public function card() {
        return $this->belongsTo(UserCard::class, 'card_id', 'id');
    }

    public function getBookingsById($col, $val) {
        return self::where($col, $val)
                        ->with('boat.user')
                        ->with('boat.BoatType')
                        ->with('user')
                        ->with('singleBookingTransaction')
                        ->first();
    }

    public function getUserUpcomingBookings($user_id) {
        return $this->where('user_id', $user_id)
                        ->where('start_date_time', ">=", Carbon::now()->timestamp)
                        ->where('is_active', 1)
                        ->get();
    }

    public function boatBookingsByDate($boat_id, $params, $user_id) {

        //dd(strtotime($params['date'].' '.$params['to_time']));

        $bookingWithBoatWithCustomer = Booking::where('boat_id', $boat_id)
                ->where('user_id', $user_id)
                ->where('start_date_time', '>', strtotime($params['date'] . ' ' . $params['from_time']))
                ->where('end_date_time', '<', strtotime($params['end_date'] . ' ' . $params['to_time']))
                ->where('is_active', 1);

        $bookingWithBoat = Booking::where('boat_id', $boat_id)
                ->where('start_date_time', '>', strtotime($params['date'] . ' ' . $params['from_time']))
                ->where('end_date_time', '<', strtotime($params['end_date'] . ' ' . $params['to_time']))
                ->where('is_active', 1);

        $record = $bookingWithBoatWithCustomer->union($bookingWithBoat)->get();

        return ($record) ? $record->toArray() : null;
    }

    public function boatBookingsByStartDateEndDate($params) {

        $record = Booking::where('boat_id', '=', $params['boat_id'])
                ->where('start_date_time', '>=', strtotime($params['start_date']))
                ->where('end_date_time', '<=', strtotime($params['end_date']))
                ->where('is_active', 1)
                ->orderBy('start_date_time', 'asc')
                ->get();
        return !empty($record) ? $record->toArray() : null;
    }

    public function getBoatBookingsByDate($boat_id, $date) {

        return $this->where('boat_id', $boat_id)->where('status', '!=', 'pending_payment')
                        //            ->where('start_date_time', '>', strtotime($date.' 00:00'))
                        //            ->where('end_date_time', '<', strtotime($date.' 23:59'))
                        ->where('status', '!=', 'pending_payment')
                        ->where('is_active', 1)->get();
    }

    public function getBoatBookings($boat_id) {
        return $this->where('boat_id', $boat_id)
                        ->where('status', '!=', 'pending_payment')
                        ->where('status', '!=', 'pending_payment')
                        ->where('is_active', 1)->get();
    }

    public function getBookingsCalendar($col, $val, $status = false) {
        $query = Booking::where($col, $val)
                ->where('is_active', 1)
                ->where('status', '!=', 'pending_payment')
                ->when($status && $status != 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->with('boat')
                ->get();

        return ($query) ? $query->toArray() : null;
    }

    public function getBookings($col, $val, $params) {
        $take = ($params['offset'] <= 0) ? 0 : $params['offset'];
        $status = $params['status'];
        $query = Booking::where($col, $val)
                ->when($status && $status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->where('is_active', 1)
                ->with('boat')
                ->skip($take)->take($params['limit'])
                ->get();

        return ($query) ? $query->toArray() : null;
    }

    public function getAdminBookings($status = false) {
        $query = $this->when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })
        ->orderBy('id','desc')
        ->with('boat.user')
        ->with('user')
        ->get();
        return ($query) ? $query->toArray() : null;
    }

    public function getBoatCustomers($boatId) {
        return $this->where('boat_id', $boatId)->distinct()->count('user_id');
    }

    public function getBoatEarning($boatId) {
        return $this->where('boat_id', $boatId)->sum('booking_price');
    }

    public function getCustomerBookingCount($user_id) {
        return Booking::where('user_id', $user_id)->count();
    }

    public function getDetailsByCol($col, $val) {

        $result = $this->where($col, $val)
                        ->with('card')
                        ->with('promoCode')
                        ->with('reviews.user')
                        ->with('user')
                        ->with('boat.user.notification_settings')
                        ->with('singleBookingTransaction')
                        ->with('refundedTransaction')
                        ->where('is_active', 1)->first();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function updateBookingStatus($column, $value, $data) {
        return Booking::where($column, '=', $value)->update($data);
    }

    protected function getBalance($col, $val, $status = []) {
        return $this->where($col, $val)
                        ->whereIn('status', $status)
                        ->where('is_active', 1)
                        //                ->where('is_transferred',0)
                        ->distinct()->sum('payment_received');
    }

    public function paymentReceivedAble($boatIds, $start_date, $end_date) {
        return self::whereIn('boat_id', $boatIds)
                        ->where('is_transferred', 0)
                        ->where('is_refund', 0)
                        ->where('status', 'completed')
                        //            ->whereBetween('end_date_time',[strtotime($start_date),strtotime($end_date)])
                        ->sum('payment_received');
    }

    public function pendingRefundAbleBookingIds($boatIds, $start_date, $end_date) {
        return self::whereIn('boat_id', $boatIds)
                        ->where('is_transferred', 0)
                        //            ->whereBetween('end_date_time',[strtotime($start_date),strtotime($end_date)])
                        ->where('is_refund', 1)
                        ->pluck('id');
    }

    protected function checkBookingsAgainstTime($boat_id, $user_id) {

        $date = strtotime(date("Y-m-d H:i:s"));
        $boat_bookings = Booking::where('boat_id', $boat_id)
                ->where('status', '<>', 'cancelled')->where('status', '<>', 'rejected')
                ->where('start_date_time', '>=', $date)
                ->where('is_active', 1);

        $user_bookings = Booking::where('user_id', $user_id)
                ->where('status', '<>', 'cancelled')->where('status', '<>', 'rejected')
                ->where('start_date_time', '>=', $date)
                ->where('is_active', 1);
        //        $query = $query->where(function ($innerQuery) use ($startDate, $endDate) {
        //            $innerQuery->whereBetween('start_date_time', [$startDate, $endDate]);
        //        });
        //        $query = $query->where(function ($innerQuery) use ($startDate, $endDate) {
        //            $innerQuery->orwhereBetween('end_date_time', [$startDate, $endDate]);
        //        });
        $result = $user_bookings->union($boat_bookings)->get();

        return !empty($result) ? $result->toArray() : [];
    }

    public function getCustomerBookings($col, $val, $params) {
        $take = ($params['offset'] <= 0) ? 0 : $params['offset'];
        $status = $params['status'];
        $query = Booking::where($col, $val)
                ->when($status && $status !== 'all', function ($q) use ($status) {
                    $q->where('status', $status);
                })
                ->where('status', '!=', 'pending_payment')
                ->where('is_active', 1)
                ->with('boat')
                ->skip($take)->take($params['limit'])
                ->get();

        return ($query) ? $query->toArray() : null;
    }

    public function getBoatBookingsList($boat_id) {
        return $this->where('boat_id', $boat_id)
                        ->whereNotIn('status', ['pending', 'confirmed'])
                        ->where('is_transferred', 0)
                        ->where('is_active', 1)
                        ->with('boat')
                        ->whereHas('bookingTransactions', function ($sql) {
                            $sql->where('transaction_status', 'refunded');
                        })
                        ->get();
    }

    public function getPreviousBooking($params) {

        $record = Booking::where('boat_id', '=', $params['boat_id'])
                ->where('start_date_time', '<', strtotime($params['start_date']))
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->first();
        return !empty($record) ? $record->toArray() : [];
    }

    public function getNextBooking($params) {

        $record = Booking::where('boat_id', '=', $params['boat_id'])
                ->where('start_date_time', '>', strtotime($params['end_date']))
                ->where('is_active', 1)
                ->orderBy('created_at', 'asc')
                ->first();
        return !empty($record) ? $record->toArray() : [];
    }

    public function getMultipleSchedulesBookings($params) {

        $record = Booking::where('boat_id', '=', $params['boat_id'])
                ->where('start_date_time', '>=', strtotime($params['start_date']))
//                ->where('end_date_time', '<=', strtotime($params['end_date']))
                ->where('is_active', 1)
                ->orderBy('start_date_time', 'asc')
                ->get();
        return !empty($record) ? $record->toArray() : null;
    }

}
