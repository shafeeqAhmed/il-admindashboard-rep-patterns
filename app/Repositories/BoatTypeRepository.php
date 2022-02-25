<?php

namespace App\Repositories;

use App\Models\BoatType;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatTypeResponse;
use Illuminate\Support\Str;

class BoatTypeRepository extends BaseRepository implements RepositoryInterface {

    use BoatTypeResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return BoatType::class;
    }

    public function getBoatTypes($params) {
        $types = $this->all();
        $response = [];
        foreach ($types as $type) {
            $response[] = $this->BoatTypeResponse($type);
        }
        return $response;
    }
    public function getAdminBoatTypes() {
        $types = $this->where('is_deleted',0)->get();
        $response = [];
        foreach ($types as $type) {
            $response[] = $this->BoatTypeResponse($type);
        }
        return $response;
    }
    public function updateAdminBoatTypes($params,$uuid) {

        return $this->getByColumn($uuid,'boat_type_uuid')->update($this->updateInputAdminBoatType($params));
    }
    public function updateInputAdminBoatType($params) {
        return [
            'name'=> $params['name'],
            'is_active'=> $params['is_active']
        ];
    }
    public function storeAdminBoatTypes($params) {

        return $this->model->create($this->storeInputAdminBoatType($params));
    }
    public function storeInputAdminBoatType($params) {
        return [
            'boat_type_uuid'=>Str::uuid()->toString(),
            'name'=> $params['name'],
            'pic'=>'https://via.placeholder.com/360x360.png/CCCCCC?text=animals+dogs+aut'
        ];
    }

    public function mapOnTable($params) {

    }

}
