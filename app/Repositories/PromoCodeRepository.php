<?php

namespace App\Repositories;


use App\Models\Boat;
use App\Models\Booking;
use App\Models\PromoCode;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\PromoCodeResponse;
use Carbon\Carbon;
//use Your Model

/**
 * Class BoatRepository.
 */
class PromoCodeRepository extends BaseRepository implements RepositoryInterface
{
 use PromoCodeResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return PromoCode::class;
    }

    public function createPromocode($params){
        $params['boat_id'] = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        return $this->promoCodeResponse($this->create($this->mapOnTable($params)));
    }

    public function getPromoCodeDetail($params){
        $promoCode = $this->model->getPromocode('code_uuid', $params['code_uuid']);
        return $this->promoCodeResponse($promoCode);
    }

    public function getPromoCodes($params){
        $boatId = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $promoCodes = $this->model->getPromocodes('boat_id', $boatId);
        return $this->makeMultiResponse($promoCodes);
    }

    public function deletePromocode($params){
        $promoCodeId = PromoCode::where('code_uuid', $params['code_uuid'])->value('id');
        $promoCode = $this->updateById($promoCodeId, ['is_active' => 0])->refresh();
        return $this->promoCodeResponse($promoCode);
    }

    public function getPromoCodesByStatus($params){
        $boatId = Boat::where('boat_uuid', $params['boat_uuid'])->value('id');
        $promoCodes = $this->model->getPromocodes('boat_id', $boatId);
        $response['active'] = [];
        $response['expired'] = [];
        foreach($promoCodes as $code){
            if(Carbon::createFromTimestamp($code['valid_to'])->gt(Carbon::now())){
                $response['active'][] = $this->promoCodeResponse($code);
            } else {
                $response['expired'][] = $this->promoCodeResponse($code);
            }
        }
         return $response;
    }

    public function checkValidity($params){
        $promocode = $this->model->checkValidity($params);

        if($promocode == null ){
            return false;
        }
        if((Carbon::now()->lt(Carbon::createFromTimestamp($promocode->valid_from)) || Carbon::now()->gt(Carbon::createFromTimestamp($promocode->valid_to)))){
            return false;
        } else {
            return $this->promoCodeResponse($promocode);
        }
    }

    public function makeMultiResponse($stories){
        $final = [];
        foreach($stories as $story){
            $final[]= $this->PromoCodeResponse($story);
        }
        return $final;
    }

    public function mapOnTable($params){
        return [
            'code_uuid'=>Str::uuid()->toString(),
            'boat_id' => $params['boat_id'],
            'coupon_code' => $params['code'],
//            'valid_from' => $params['start_date'],
            'valid_from' =>  Carbon::createFromFormat('Y-m-d', $params['start_date'])->timestamp,
            'valid_to' =>  Carbon::createFromFormat('Y-m-d', $params['end_date'])->timestamp,
//        'valid_to' => $params['end_date'],
            'coupon_amount' => $params['percentage'],
            'discount_type' => 'percentage',
        ];
    }

}
