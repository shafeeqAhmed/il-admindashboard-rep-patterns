<?php

namespace App\Repositories;


use App\Models\BoatDefaultService;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatDefaultServicesResponse;
use Illuminate\Support\Str;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatDefaultServicesRepository extends BaseRepository implements RepositoryInterface
{
    use BoatDefaultServicesResponse;
    /**
     * @return string
     *  Return the model
     */

    public function model()
    {
        return BoatDefaultService::class;
    }
    public function getDefaultServices() {
        return $this->makeMultiResponse($this->model->getDefaultServices());

    }

    public function makeMultiResponse($services){
        $final = [];
        foreach($services as $service){
            $final[]= $this->BoatDefaultServiceResponse($service);
        }
        return $final;
    }

    public function mapOnTable($params){
        return [
            'boat_service_uuid'=>Str::uuid()->toString(),
            'name'=>$params['name'],
            'boat_id'=>$params['boat_id']
        ];
    }

}
