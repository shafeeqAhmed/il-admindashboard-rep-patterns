<?php

namespace App\Repositories;


use App\Models\BoatFavorite;
use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatFavoriteResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class BoatFavoriteRepository extends BaseRepository implements RepositoryInterface
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
    public function addBoatFavorite($params){
        if($params['type'] == 'favorite') {
            return $this->favorite($params);
        }
        if($params['type'] == 'unfavorite') {
           return  $this->unfavourite($params);
        }
    }

    public function favorite($params) {
        $input = $this->mapOnTable($params);
        $data = [];
        if(!$this->model->isBoatFavoriteExist($input['boat_id'],$input['user_id'])) {
              $this->boatFavoriteResponse($this->create($input));
            $data['message'] = 'Add Favorite Successfully!';
            $data['status']  = true;
        }else {
            $data['message'] = 'Favorite Already Exist!';
            $data['status']  = false;
        }
            return $data;
    }

    public function getFavouriteBoatsByUser($user_id){
        return $this->model->getFavouriteBoatsCount($user_id);
    }

    public function unfavourite($params) {
        $input = $this->mapOnTable($params);
        $data = [];
        if($this->model->isBoatFavoriteExist($input['boat_id'],$input['user_id'])) {
            $this->where('boat_id',$input['boat_id'])->where('user_id',$input['user_id'])->delete();
            $data['message'] = 'Un Favorite Successfully!';
            $data['status']  = true;
        } else {
            $data['message'] = 'Favorite Does not Exist!';
            $data['status']  = false;
        }
        return $data;
    }

    public function getUserFavouriteBoats($params){
        $final = [];
        $userId = User::where('user_uuid',$params['user_uuid'])->value('id');
        $favourites = $this->model->getFavouriteBoatsByUser($userId);
        if(!empty($favourites)){
            foreach($favourites as $boat){
                $final[]  = (new BoatRepository())->boatResponse($boat['boat']);
            }
        }
        return $final;
    }

    public function checkBoatLiked($userId, $boatId){
        return $this->model->isBoatFavoriteExist($boatId, $userId);
    }


    public function mapOnTable($params){
        return [
            'favourite_uuid' => Str::uuid()->toString(),
            'boat_id'=> (new BoatRepository)->getByColumn($params['boat_uuid'],'boat_uuid',['id'])->id,
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
        ];
    }

}
