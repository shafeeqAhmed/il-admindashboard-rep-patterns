<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasApiTokens,
        HasFactory,
        Notifiable;

    protected $guarded = ['id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function format()
    {
        return [
            'user_name' => $this->first_name . ' ' . $this->last_name,
            'user_email' => $this->email
        ];
    }

    public function notification_settings(){
        return $this->hasOne(NotificationSetting::class, 'user_id','id');
    }
    public function bankAccountDetail()
    {
        return $this->hasOne(BankDetail::class, 'user_id', 'id');
    }
    public function updateData($col, $val, $data = [])
    {
        $result = User::where($col, $val)->update($data);
        return ($result) ? true : false;
    }
    public function getBoatOwnerDetail($col, $val)
    {
        return $this->where($col, $val)->with('boats.BoatType', 'bankAccountDetail')->first();
    }
    public function getUserByColumn($val,$col,$role) {
        return $this->where($col,$val)->where('role',$role)->first();
    }

    public function boats()
    {
        return $this->hasMany(Boat::class, 'user_id', 'id');
    }

    public function getCountries()
    {
        $result = self::where('is_active', '=', 1)->groupBy('country_name')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public function getCities($search)
    {
        $result = self::where('is_active', '=', 1)->where('country_name', '=', $search)->groupBy('city')->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public function getUserById($userId)
    {
        return User::where('id', $userId)->first();
    }
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getUserByEmailandPhone($user) {
        return User::where('phone_number',$user['phone_number'])->where('email',$user['email'])->where('role',$user['role'])->first();
    }



    protected function getUserByUUId($userUuid)
    {
        $result = User::where('user_uuid', $userUuid)->where('is_active', 1)->first();
        return $result ? $result->toArray() : [];
    }
    public function getBoatOwners($status = false)
    {
        return $this->getUsers('boat_owner', $status);
    }

    public function getCustomers($status = false)
    {
        return $this->getUsers('customer', $status);

        //        $query = $this->when($status, function ($q) use ($status) {
        //            $q->where('status', $status);
        //        })
        //            ->where('role','customer')
        //            ->get();
        //        return ($query) ? $query->toArray() : null;
    }
    public function messageCodes()
    {
        $query = $this->select('first_name', 'last_name', 'phone_number', 'email', 'verification_code', 'status', 'created_at')
            //        ->whereDate('created_at', '>=', date('Y-m-d'))
            ->orderBy('id', 'DESC')->get();
        return ($query) ? $query->toArray() : null;
    }
    public function getUsers($role, $type)
    {

        $where = $this->getWereCluse($type, $role);

        $query = self::where($where);

        $query = $this->getRelationship($role, $query)->orderBy('id', 'DESC')->get();
        return ($query) ? $query->toArray() : null;
    }
    private function getWereCluse($type, $role)
    {
        $where = [];
        //selection for condition
        if ($type == 'active') {
            $where = ['is_active' => 1, 'is_verified' => 1];
        } elseif ($type == 'not_verified') {
            $where = ['is_verified' => 0];
        } elseif ($type == 'blocked') {
            $where = ['status' => 'blocked'];
        } elseif ($type == 'deleted') {
            $where = ['status' => 'deleted'];
        }
        $where['role'] = $role;
        return $where;
    }
    private function getRelationship($role, $query)
    {
        if ($role == 'customer') {
        } elseif ($role == 'boat_owner') {
            $query->with('boats');
        }
        return $query;
    }

    public function getOwnerBoatBookings($uuid, $type = "all")
    {
        return User::with('boats.bookings.user', 'boats.bookings.refundedTransaction')
        ->with(['boats.bookings'=>function($sql) use($type){
            $sql->whereNotIn('status',['cancelled','rejected']);
            $sql->when($type == 'transfered', function ($nstq){
                $nstq->where('is_transferred', 1);
                $nstq->orderByDesc('id');
            });
            $sql->when($type == 'available' || $type == 'pending', function ($nstq){
                $nstq->where('is_transferred', 0);
                $nstq->whereIn('status',['completed','confirmed']);
            });
            $sql->orderByDesc('id');
        }])
        ->where('user_uuid', $uuid)->first();
    }
}
