<?php

namespace App\Repositories;

use App\Helpers\MessageHelper;
use App\Models\Boat;
use App\Models\BoatCaptain;
use App\Models\BoatFavorite;
use App\Models\BoatType;
use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Facades\Redirect;
use App\Traits\Responses\BoatResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatRepository extends BaseRepository implements RepositoryInterface {

    use BoatResponse;

    /**
     * @return string
     *  Return the model
     */
    public  function model() {
        return Boat::class;
    }

    //    public function createBoat($params){
    //
    //        if(!isset($params['boat_uuid'])){
    //            $boatUuid = $this->create($this->boatDetail($params))->boat_uuid;
    //            (new BoatWorkingHoursRepository())->addBoatWorkingHours($boatUuid);
    //
    //        }else{
    //
    //            // update boat here
    //            $boat = $this->getByColumn($params['boat_uuid'],'boat_uuid')->first();
    //            $boatDetail = $this->boatDetail($params,$boat);
    //
    //            if(isset($params['boat_profile_pic'])){
    //                $boatDetail['profile_pic'] = $params['boat_profile_pic'];
    //            }
    //            $this->model->updateBoat($boatDetail);
    //            $boatUuid = $params['boat_uuid'];
    //        }
    //        $this->updateBoundNameAndCount($boatUuid, $params['boat_onboard_name']);
    //
    //        return $this->getBoatDetail($boatUuid);
    //    }

    public function createBoat($params) {

        if (!isset($params['boat_uuid'])) {
            $boat = $this->create($this->boatDetail($params));
            if (isset($params['boat_profile_pic'])) {
                $boatImagesRepository = new BoatImagesRepository();
                $boatImagesRepository->createMultiple($boatImagesRepository->createMultipleImagesObject($params['boat_profile_pic'], $boat->id));
            }
            (new BoatWorkingHoursRepository())->addBoatWorkingHours($boat->boat_uuid);
        } else {
            // update boat here
            $boat = $this->model->where('boat_uuid', $params['boat_uuid'])->first();
            $boatDetail = $this->boatDetail($params, $boat);

            if (isset($params['boat_profile_pic'])) {
                $boatImagesRepository = new BoatImagesRepository();
                $boatImagesRepository->createMultiple($boatImagesRepository->createMultipleImagesObject($params['boat_profile_pic'], $boat->id));
            }
            $this->model->updateBoat($boatDetail);
        }
        $this->updateBoundNameAndCount($boat->boat_uuid, $params['boat_onboard_name']);

        return $this->getBoatDetail($boat);
    }

    public function getBoatDetail($params) {
        $userId = false;

//         if (empty(getallheaders()['apikey'])) {
//             preg_match("/iPhone|Android|iPad|iPod|webOS|Linux/", $_SERVER['HTTP_USER_AGENT'], $matches);
//             $os = current($matches);
//             switch ($os) {
//                 case 'iPhone':
//                     return Redirect::route('install-app');
// //                return redirect('https://apps.apple.com/us/app/facebook/id284882215');
//                     break;
//                 case 'Android':
//                     return Redirect::route('install-app');
// //                return redirect('https://play.google.com/store/apps');
//                     break;
//                 case 'iPad':
//                     return Redirect::route('install-app');
// //                return redirect('itms-apps://itunes.apple.com/us');
//                     break;
//                 case 'iPod':
//                     return Redirect::route('install-app');
// //                return redirect('itms-apps://itunes.apple.com/us');
//                     break;
//                 case 'webOS':
//                     return Redirect::route('install-app');
// //                return redirect('https://apps.apple.com/us');
//                     break;
//                 case 'Linux':
// //                return Route::view('/welcome', 'welcome');
//                     return Redirect::route('install-app');
// //                return redirect('https://apps.apple.com/us');
//                     break;
//                 default:
//                     return Redirect::route('install-app');
//             }
//         }
        if (isset($params['user_uuid'])) {
            $userId = (new UserRepository())->getByColumn($params['user_uuid'], 'user_uuid', ['id'])->id;
        }

        return $this->boatResponse($this->model->getBoatDetail($params['boat_uuid']), $userId);
    }

    public function getBoatsWithType($data) {
        $type_id = null;
        $response = [];
        if ((isset($data['boat_type_uuid'])) && (!empty($data['boat_type_uuid']))) {
            $type = BoatType::getTypeWithUUID('boat_type_uuid', $data['boat_type_uuid']);
            $type_id = !empty($type) ? $type['id'] : null;
        }
        $boats = $this->model->getBoats('status', 'active', $type_id, $data);
        if (!empty($boats)) {
            foreach ($boats as $boat) {
                if (!empty($boat)) {
                    $response[] = $this->boatResponse($boat);
                }
            }
        }
        return $response;
    }

    //    public function updateBoatLocation($params) {
    //        $this->model->updateRecordsByUuid($params['boat_uuid'], $this->boatLocation($params));
    //        $this->updateBoundNameAndCount($params['boat_uuid'], $params['boat_onboard_name']);
    //        return $this->getBoatDetail($params['boat_uuid']);
    //    }

    public function updateBoatLocation($params) {
        $boatDocumentRepository = new BoatDocumentRepository();
        $requiredDocumentRepository = new RequiredDocumentRepository();
        $document_uuids = [];
        foreach ($params['boat_documents'] as $key => $array) {
            if (!empty($array['document_uuid'])) {
                if (!in_array($array['document_uuid'], $document_uuids)) {
                    array_push($document_uuids, $array['document_uuid']);
                }
            }
        }
//        $collection = collect($params['boat_documents']);
//        if (!empty($collection)) {
//            $document_uuids = $collection->map(function ($value, $key) {
//                return $value['document_uuid'];
//            });
//        }
        $requiredDocs = $requiredDocumentRepository->getRecordsByUuids($document_uuids);

        if ($params['boat_documents']) {
            $boatId = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
            $boatDocumentRepository->model->insert($boatDocumentRepository->makeBulkInsertDocumentObject($params['boat_documents'], $boatId, $requiredDocs));
        }
        $this->updateBoundNameAndCount($params['boat_uuid'], $params['boat_onboard_name']);
        return $this->getBoatDetail($params);
    }

    public function filterBoats($params) {

        $final = [];
        foreach ($this->model->filterBoats($params) as $boat) {
            $final[] = $this->boatResponse($boat);
        }
        return $final;
    }

    public function searchMapBoats($params) {
        $final = [];
        $boats = $this->model->filterBoats($params);
        foreach ($boats as $boat) {
            $final[] = $this->boatResponse($boat);
        }
        return $final;
    }

    public function updateBoundNameAndCount($boatUuid, $name) {
        return $this->model->updateBoundNameAndCount($boatUuid, $name);
    }

    public function boatLocation($params) {
        return [
            'location' => $params['boat_location'],
            'lat' => $params['boat_lat'],
            'lng' => $params['boat_lng'],
            'state' => $params['boat_state'],
            'country' => $params['boat_country'],
        ];
    }

    public function addBoatPrice($params) {
        $boatPrice = $this->model->updateRecordsByUuid($params['boat_uuid'], ['price' => $params['price_per_hour']]);
        $this->updateBoundNameAndCount($params['boat_uuid'], $params['boat_onboard_name']);
        if (isset($params['discount'][0])) {
            return (new BoatDiscountRepository())->addBoatDiscount($params);
        } else {
            return $this->getBoatDetail($params);
        }
    }

    public function boatInfo($params) {
        return [
            'name' => (isset($params['boat_name'])) ? $params['boat_name'] : null,
            'number' => (isset($params['boat_number'])) ? $params['boat_number'] : null,
            'manufacturer' => (isset($params['boat_manufacturer'])) ? $params['boat_manufacturer'] : null,
            'info' => (isset($params['boat_info'])) ? $params['boat_info'] : null,
        ];
    }

    public function getUserBoats($userId) {
        $final = [];
        foreach ($this->model->getUserBoats($userId) as $boat) {
            $final[] = $this->boatResponse($boat);
        }
        return $final;
    }

    public function boatDetail($params) {
        if (isset($params['boat_uuid'])) {
            return [
                'boat_uuid' => $params['boat_uuid'],
                'boat_type_id' => BoatType::where('boat_type_uuid', $params['boat_boat_type_uuid'])->value('id'),
                'onboard_name' => $params['boat_onboard_name'],
                'user_id' => User::where('user_uuid', $params['boat_user_uuid'])->value('id'),
                'location' => $params['boat_location'],
                'lat' => $params['boat_lat'],
                'lng' => $params['boat_lng'],
                'state' => $params['boat_state'],
                'country' => $params['boat_country'],
                'name' => (isset($params['boat_name'])) ? $params['boat_name'] : null,
                'number' => (isset($params['boat_number'])) ? $params['boat_number'] : null,
                'info' => (isset($params['boat_info'])) ? $params['boat_info'] : null,
                'profile_pic' => (isset($params['boat_default_image'])) ? $params['boat_default_image'] : null
            ];
        } else {
            return [
                'boat_uuid' => Str::uuid()->toString(),
                'boat_type_id' => BoatType::where('boat_type_uuid', $params['boat_boat_type_uuid'])->value('id'),
                'onboard_name' => $params['boat_onboard_name'],
                'user_id' => User::where('user_uuid', $params['boat_user_uuid'])->value('id'),
                'location' => $params['boat_location'],
                'lat' => $params['boat_lat'],
                'lng' => $params['boat_lng'],
                'state' => $params['boat_state'],
                'country' => $params['boat_country'],
                'name' => (isset($params['boat_name'])) ? $params['boat_name'] : null,
                'number' => (isset($params['boat_number'])) ? $params['boat_number'] : null,
                'info' => (isset($params['boat_info'])) ? $params['boat_info'] : null,
                'profile_pic' => (isset($params['boat_default_image'])) ? $params['boat_default_image'] : null
            ];
        }
    }

    public function getBoatCalender($params) {
        $boatId = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $boatWorkingHorusRepository = new BoatWorkingHoursRepository();
        $bookingRepository = new BookingRepository();
        $boatWorkingHours = $boatWorkingHorusRepository->makeMultipleResponse($boatWorkingHorusRepository->model->getSchedules($boatId));
        $boatBookings = $bookingRepository->mapMulitpleResponse($bookingRepository->model->getBookingsCalendar('boat_id', $boatId));
        return ['bookings' => $boatBookings, 'schedules' => $boatWorkingHours];
    }

    public function getBoatDashboard($params) {
        $boatId = (new BoatRepository())->getBoatIdByUuid($params['boat_uuid']);
        $response = $this->boatResponse($this->model->getBoatDetail($params['boat_uuid']));
        $response['customers_count'] = (new BookingRepository())->model->getBoatCustomers($boatId);
        $response['earning_count'] = (new BookingRepository())->model->getBoatEarning($boatId);
        return $response;
    }

    public function getBoatBookings($params) {

        $boatId = (new BoatRepository())->getBoatIdByUuid($params['boat_uuid']);
        $response = (new BookingRepository())->model->getBoatBookings($boatId, $params['type']);
        return $response;
    }

    public function removeBoat($params) {
        return $this->model->updateRecordsByUuid($params['boat_uuid'], ['is_active' => 0]);
    }

    public function getBoatSettings($params) {
        return ['balance' => 3000, 'notification' => false];
    }

    public function updateCaptain($params) {
        $userRepository = new UserRepository();
        $userId = BoatCaptain::where('captain_uuid', $params['captain_uuid'])->value('id');
        $params['user_uuid'] = $userRepository->getById($userId)->value('user_uuid');
        return $userRepository->updateUser(Arr::except($params, 'captain_uuid'));
    }

    public function getFavouriteBoat($params) {

    }

    public function getAdminBoats($col, $val) {
        return $this->model->getAdminBoats($col, $val);
    }

    public function mapOnTable($params) {

        return [
            'name_number' => $params['boat_name_number'],
            'manufacturer' => $params['boat_manufacturer'],
            'user_id' => $params['boat_user_id'],
            'boat_type_id ' => $params['boat_boat_type_id'],
            'onboard_name' => $params['boat_onboard_name'],
            'profile_pic' => (isset($params['boat_profile_pic'])) ? $params['boat_profile_pic'] : null,
            'info' => (isset($params['boat_info'])) ? $params['boat_info'] : null,
            'location' => (isset($params['boat_location'])) ? $params['boat_location'] : null,
            'lat' => (isset($params['boat_lat'])) ? $params['boat_lat'] : null,
            'lng' => (isset($params['boat_lng'])) ? $params['boat_lng'] : null,
            'state' => (isset($params['boat_state'])) ? $params['boat_state'] : null,
            'country' => (isset($params['boat_country'])) ? $params['boat_country'] : null,
            'price' => (isset($params['boat_price'])) ? $params['boat_price'] : null,
            'price_unit' => (isset($params['boat_price'])) ? $params['boat_price_unit'] : null,
        ];
    }

    public function getBoatBookingsList($uuid) {
        $boatId = (new BoatRepository())->getBoatIdByUuid($uuid);
        $response = (new BookingRepository())->model->getBoatBookingsList($boatId);
        return $response;
    }

    public function addBoatBookingWithdraw($params) {
        return Withdraw::addBoatBookingWithdraw($this->mapOnWithdrawTable($params));
    }

    public function mapOnWithdrawTable($params) {
        return [
            'withdraw_uuid' => Str::uuid()->toString(),
            'user_id' => $params['user_id'],
            'amount ' => $params['amount'],
            'transaction_charges' => $params['amount'],
            'receipt_id' => (isset($params['receipt_id'])) ? $params['receipt_id'] : null,
            'receipt_url' => (isset($params['receipt_url'])) ? $params['receipt_url'] : null,
        ];
    }

}
