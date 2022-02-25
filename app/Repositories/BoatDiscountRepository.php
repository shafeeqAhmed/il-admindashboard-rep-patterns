<?php

namespace App\Repositories;

use App\Models\BoatPriceDiscount;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\BoatDiscountResponse;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Str;
//use Your Model

/**
 * Class BoatDiscountRepository.
 */
class BoatDiscountRepository extends BaseRepository implements RepositoryInterface
{
    use BoatDiscountResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatPriceDiscount::class;
    }

    public function addBoatDiscount($params){
        $finalRecords = [];
        $boatRepository = new BoatRepository();

        $this->model->deleteRecordsById($boatRepository->getBoatIdByUuid($params['boat_uuid']));
       foreach($params['discount'] as $record){
            $record['boat_id'] = $boatRepository->getBoatIdByUuid($params['boat_uuid']);
            $finalRecords[] = $this->mapOnTable($record);
       }
        $this->updateOrCreateRecords($finalRecords,'discount_uuid','discount_uuid');
        return $boatRepository->getBoatDetail($params);
    }


    public function makeMultiResponse($records){
        $final = [];
        foreach($records as $record){
           $final[] = $this->boatDiscountResponse($record);
        }
        return $final;
    }


    public function mapOnTable($params){
        return [
            'discount_uuid'=>(isset($params['discount_uuid']))?$params['discount_uuid']:Str::uuid()->toString(),
            'discount_after'=>$params['after'],
            'percentage'=>$params['percent'],
            'boat_id'=>$params['boat_id']
        ];
    }

    public function updateOrCreateRecords(array $records,$databseColoum,$arrayColoum){
        foreach($records as $param){
            $this->updateOrCreateRecord($param,$databseColoum,$arrayColoum);
        }
        return true;
    }
    public function updateOrCreateRecord(array $param,$databseColoum,$arrayColoum){
        $firstRecord = $this->getByColumn($param[$arrayColoum],$databseColoum);
        if($firstRecord == null){
            $this->create($param);
        }else{
            $this->model->where($databseColoum,$param[$arrayColoum])->update($param);
        }
    }
}
