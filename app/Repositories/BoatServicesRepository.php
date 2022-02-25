<?php

namespace App\Repositories;


use App\Models\BoatService;
use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatServicesResponse;
use Illuminate\Support\Str;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatServicesRepository extends BaseRepository implements RepositoryInterface
{
 use BoatServicesResponse;
    /**
     * @return string
     *  Return the model
     */

    public function model()
    {
        return BoatService::class;
    }

    public function getBoatServices($boatUuid) {
        $boat = (new BoatRepository())->getByColumn($boatUuid,'boat_uuid');
        return  $boat ?  $boat->BoatServices->toArray() : [];
    }
    public function getServices($params) {
        $data['custom'] = [];
        if(isset($params['boat_uuid'])) {
            $data['custom'] = $this->makeMultiResponse($this->getBoatServices($params['boat_uuid']));
        }
        $data['default'] = (new BoatDefaultServicesRepository())->getDefaultServices();
        return $data;
    }

    public function createServices($params){
        $finalCustomRecords = [];
        $finalDefaultRecords = [];

        $boatDefaultServiceRepository = new BoatDefaultServicesRepository();
        $boat_id = (new BoatRepository())->getByColumn($params['boat_uuid'],'boat_uuid')->id;

        //for custom services
        if(isset($params['custom_services'])){
            $previousServices = collect($this->getBoatServices($params['boat_uuid']));
            foreach($params['custom_services'] as $service){
                $filtered = $previousServices->filter(function ($value) use($service) {
                    return $value['name'] == $service['name'];
                })->toArray();
                if(empty($filtered)){
                    $service['boat_id'] = $boat_id;
                    $service['is_approved'] = 0;
                    $finalCustomRecords[] =$this->mapOnTable($service);
                }
            }
            $this->createMultiple($finalCustomRecords);
        }


        //for default services
        if(isset($params['default_services'])){
            $defaultServices = $boatDefaultServiceRepository->model->whereIn('boat_default_service_uuid', $params['default_services'])->get();
            $previousServices = collect($this->getBoatServices($params['boat_uuid']));

            foreach($defaultServices as $service){
                $filtered = $previousServices->filter(function ($value) use($service) {
                    return $value['name'] == $service['name'];
                })->toArray();
                if(empty($filtered)){
                    $service['boat_id'] = $boat_id;
                    $service['is_approved'] = 1;
                    $finalDefaultRecords[] =$this->mapOnTable($service);
                }
            }
            $this->createMultiple($finalDefaultRecords);
        }



        $boatRepository = new BoatRepository();
        $boatRepository->updateBoundNameAndCount($params['boat_uuid'],$params['boat_onboard_name']);
        $boatRepository->model->where('boat_uuid', $params['boat_uuid'])->update(['capacity' => $params['boat_capacity']]);
        return $boatRepository->getBoatDetail($boatRepository->getByColumn($params['boat_uuid'],'boat_uuid'));
    }
    public function deleteServices($params){
        $this->model->deleteServices($params);
        $boatRepository = new BoatRepository();
        return $boatRepository->getBoatDetail($boatRepository->getByColumn($params['boat_uuid'],'boat_uuid')->boat_uuid);
    }
    public function makeMultiResponse($services){
        $final = [];
        foreach($services as $service){
          $final[]= $this->ServiceResponse($service);
        }
        return $final;
    }

    public function mapOnTable($params){
        return [
            'boat_service_uuid'=>Str::uuid()->toString(),
            'name'=>$params['name'],
            'boat_id'=>$params['boat_id'],
            'is_approved'=>$params['is_approved'],
            'default_service_id'=>(isset($params['boat_default_service_uuid']))?$params['id']:null
        ];
    }

}
