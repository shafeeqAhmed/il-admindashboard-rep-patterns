<?php

namespace App\Repositories;


use App\Models\BoatFavorite;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatFavoriteResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class AccountRepository extends BaseRepository implements RepositoryInterface
{
 use BoatFavoriteResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatFavorite::class;
    }

    public function mapOnTable($params){
        return [
            'favourite_uuid' => Str::uuid()->toString(),
            'boat_id'=> (new BoatRepository)->getByColumn($params['boat_uuid'],'boat_uuid',['id'])->id,
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
        ];
    }

}
