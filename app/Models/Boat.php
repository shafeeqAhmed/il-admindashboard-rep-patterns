<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class Boat extends Model {

    use HasFactory;

    protected $guarded = ['id'];

    public function BoatType() {
        return $this->hasOne(BoatType::class, 'id', 'boat_type_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function boat_images() {
        return $this->hasMany(BoatImage::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function BoatServices() {
        return $this->hasMany(BoatService::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function boatCustomServices() {
        return $this->hasMany(BoatService::class, 'boat_id', 'id')->where('is_active', 1)->whereNull('default_service_id');
    }
    public function boatDefualtServices() {
        return $this->hasMany(BoatService::class, 'boat_id', 'id')->where('is_active', 1)->whereNotNull('default_service_id');
    }
    public function boat_documents(){
        return $this->hasMany(BoatDocument::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function BoatCaptains() {
        return $this->hasMany(BoatCaptain::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function discount() {
        return $this->hasMany(BoatPriceDiscount::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function posts() {
        return $this->hasMany(BoatPost::class, 'boat_id', 'id')->where('is_active',1);
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'boat_id', 'id');
    }

    public function stories() {
        return $this->hasMany(BoatStories::class, 'boat_id', 'id')->where('is_active', 1)->latest();
    }

    public function ActiveStories() {
        return $this->hasMany(BoatStories::class, 'boat_id', 'id')
                        ->whereBetween('created_at', [now()->subMinutes(1440), now()])
                        ->where('is_active', '=', 1)
                        ->orderBy('created_at', 'desc');
    }

    public function reviews() {
        return $this->hasMany(BoatReview::class, 'boat_id', 'id')->where('is_active', 1);
    }

    public function updateBoundNameAndCount($uuid, $name) {
        $onBoardName = $this->checkOnBoardCondition(Boat::where('boat_uuid', $uuid)->first(), $name);
        // dd($onBoardName);
        if ($onBoardName) {
            Boat::where('boat_uuid', $uuid)->update(['onboard_name' => $name]);
        }
        return true;
    }

    public function updateRecordsByUuid($uuid, $params) {
        return Boat::where('boat_uuid', $uuid)->update($params);
    }

    public function checkOnBoardCondition($boat, $name) {
        $bordExplode = explode('__', $boat->onboard_name);
        $paramName = explode('__', $name);

        if (isset($bordExplode[0]) && $paramName[0] <= $bordExplode[0]) {
            return false;
        } else {
            return true;
        }
    }

    public function getBoatDetail($boatUuId) {
        $result = Boat::where('boat_uuid', $boatUuId)
                        ->with([
                            'BoatType',
                            'BoatServices',
                            'boatCustomServices',
                            'boatDefualtServices',
                            'BoatCaptains.captain_user',
                            'discount',
                            'posts',
                            'reviews.user',
                            'stories.boat',
                            'boat_images',
                            'boat_documents.required_documents'
                        ])
                        ->withCount('bookings')
                        ->first();
        return !empty($result) ? $result->toArray() : [];
    }

    public function getUserBoats($userId) {
        return Boat::where('user_id', $userId)
//            ->where('is_active', 1)
            ->with([
                    'BoatType',
                    'BoatServices',
                    'BoatCaptains.captain_user',
                    'discount',
                    'posts',
                    'boat_images',
                    'boat_documents'
                ])->get()->toArray();
    }

    public function filterBoats($params) {
        $query = Boat::where('onboard_name', '5__add_price')
            ->where('is_approved',1)
            ->where('is_active', 1);
        if (isset($params['search_key']) && !empty($params['search_key'])) {
            $search_key = $params['search_key'];
            $query = $query->where('name', 'like', "%$search_key%");
            $query = $query->orwhere('number', 'like', "%$search_key%");
            $query = $query->orWhere('manufacturer', 'like', "%$search_key%");
            $query = $query->orWhere('info', 'like', "%$search_key%");
        }

        if (!empty($params['boat_type'])) {
            $query->whereHas('BoatType', function ($q) use ($params) {
                $boat_type_uuids = explode(',', $params['boat_type']);
                $q->whereIn('boat_type_uuid', $boat_type_uuids);
            });
        }
        if (isset($params['captain'])) {
            if ($params['captain'] == 1) {
                $query->whereHas('BoatCaptains');
            } else {
                $query->whereDoesntHave('BoatCaptains');
            }
        }


        if ((isset($params['start_price']) && isset($params['end_price'])) && (!empty($params['start_price']) && !empty($params['end_price']))) {
            $query->whereBetween('price', [$params['start_price'], $params['end_price']]);
        } else {
            if (isset($params['start_price']) && !empty($params['start_price'])) {
                $query->where('price', '>=', $params['start_price']);
            }
            if (isset($params['end_price']) && !empty($params['end_price'])) {
                $query->where('price', '<=', $params['end_price']);
            }
        }
        if (!empty($params['city'])) {
            $query->where('city', strtolower($params['city']));
        }
        if (!empty($params['country'])) {
            $query->where('country', strtolower($params['country']));
        }



        if (isset($params['gallery'])) {
            if ($params['gallery'] == 1) {
                $query->whereNotNull('profile_pic');
            } else {
                $query->whereNull('profile_pic');
            }
        }

        if (!empty($params['lat']) && (!empty($params['lng']))) {
            self::applyDistanceFilterWithRadiusPoints($query, $params);
        }

         $query->with([
                    'BoatType',
                    'BoatServices',
                    'BoatCaptains.captain_user',
                    'discount',
                    'posts',
                    'boat_images'
                ]);
        $take = isset($params['offset']) ? (($params['offset'] <= 0) ? 0 : $params['offset']) : 0;
        if(isset($params['limit'])) {
            $query->skip($take)->take($params['limit']);
        }
        $query->orderBy('id', 'desc');
        $result = $query->get();
        return $result ? $result->toArray() : [];
    }

    public static function applyDistanceFilterWithRadiusPoints($query, $params) {

        $start_radius = isset($params['start_radius']) ? ($params['start_radius'] ?? 0) : 0;
        $haversine = "(6371 * acos(cos(radians(" . $params['lat'] . "))
                        * cos(radians(`lat`))
                        * cos(radians(`lng`)
                        - radians(" . $params['lng'] . "))
                        + sin(radians(" . $params['lat'] . "))
                        * sin(radians(`lat`))))";
        //set default start radius 0
        $start_radius = isset($params['start_radius']) ? ($params['start_radius'] ?? 0) : 0;
        $query->whereRaw("{$haversine} > " . $start_radius);
        //if end radius is not empty
        if (!empty($params['end_radius'])) {
            $query->whereRaw("{$haversine} < " . $params['end_radius']);
        }
        $query->select('*')
                ->selectRaw("{$haversine} AS distance")
                ->orderBy('distance', 'ASC');

        if (!empty($params['end_radius'])) {
            $query->whereRaw("{$haversine} > " . $start_radius)
                    ->whereRaw("{$haversine} < " . $params['end_radius']);
        }
        return $query;
    }

    public function getBoats($col, $val, $type_id = null, $params = []) {
        $query = Boat::where($col, $val)
                ->where('is_active', 1)
                ->where('is_approved', 1);
        if (!empty($type_id)) {
            $query = $query->where('boat_type_id', $type_id);
        }
        if ((isset($params['lat'])) && (!empty($params['lat'])) && (isset($params['lng'])) && (!empty($params['lng']))) {
            $query = self::applyDistanceFilterWithRadiusPoints($query, $params);
        }

        $query = $query->with([
            'BoatType',
            'BoatServices',
            'BoatCaptains.captain_user',
            'discount',
            'posts',
            'boat_images'
        ]);

        $take = isset($params['offset']) ? (($params['offset'] <= 0) ? 0 : $params['offset']) : 0;
        if(isset($params['limit'])) {
            $query->skip($take)->take($params['limit']);
        }

        $result = $query->get();
        return $result ? $result->toArray() : [];
    }
    public function getAdminBoats($col,$val) {
        $result = $this->where($col,$val)->with(['BoatType','user'])->get();
        return $result ? $result->toArray() : [];

    }

    protected function getStories($column, $value) {
        $query = Boat::where($column, '=', $value)
                ->with('ActiveStories')
                ->whereHas('ActiveStories');
        $result = $query->get();
        return !empty($result) ? $result->toArray() : [];
    }

    protected function getBoatId($col, $val) {
        $result = Boat::where($col, $val)->where('is_active', 1)->where('is_approved', 1)->first();
        return !empty($result) ? $result->toArray() : [];
    }

    public function updateBoat($params) {
        return self::where('boat_uuid', $params['boat_uuid'])->update($params);
    }

}
