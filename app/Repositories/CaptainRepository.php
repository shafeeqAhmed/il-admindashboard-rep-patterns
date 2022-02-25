<?php

namespace App\Repositories;

use App\Models\BoatCaptain;

use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\CaptainResponse;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Models\Boat;
use Faker\Generator as faker;
use Arr;
//use Your Model

/**
 * Class CaptainRepository.
 */
class CaptainRepository extends BaseRepository implements RepositoryInterface
{
    use CaptainResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatCaptain::class;
    }

    public function addCaptain($params){
        $userRepository = new UserRepository();
        $boatRepository = new BoatRepository();

        if(isset($params['captain'])){
            foreach($params['captain'] as $param){
                $user = $userRepository->createCaptain($this->captainMapper($param,$userRepository->generateVerificationCode()));
                $param['boat_id'] =$boatRepository->getByColumn( $params['boat_uuid'],'boat_uuid')->id;
                $param['user_id'] = $user->id;
                $this->create($this->mapOnTable($param));
            }
        } else {
            $boatId = Boat::where("boat_uuid", $params['boat_uuid'])->value('id');
            $this->model->deleteCaptainByBoatId($boatId);
        }

        $boatRepository->updateBoundNameAndCount($params['boat_uuid'],$params['boat_onboard_name']);
        return $boatRepository->getBoatDetail($params['boat_uuid']);
    }

    public function deleteCaptain($params){
        return $this->model->deleteCaptain($params);
    }

    public function updateCaptain($params){
        $userRepository = new UserRepository();
        $user = $this->model->where('captain_uuid',$params['captain_uuid'])->with('captain_user')->first();
//        $data = ['first_name' => $params['captain'][0]['name'], 'profile_pic' => $params['captain'][0]['image'], 'user_uuid' => $user->captain_user->user_uuid];
        $data = customMapOnTable($params['captain'][0], null);
        $data['user_uuid'] = $user->captain_user->user_uuid;
        $userRepository->updateUser($data);
        return $this->captainResponse($this->model->getCaptain('captain_uuid', $params['captain_uuid']));
    }

    public function captainMapper($params,$code){

        $faker = Factory::create();
        return [
          'user_uuid'=>Str::uuid()->toString(),
          'first_name'=>$params['name'],
          'last_name'=>'captain',
          'email'=>$faker->unique()->safeEmail(),
          'password'=>Hash::make(123456),
          'role'=>'captain',
          'status'=>'active',
          'profile_pic'=>$params['image'],
          'verification_code'=>$code
        ];
    }

    public function makeMultiResponse($captains){
        $finalCaptains = [];
        foreach($captains as $captain){
            $finalCaptains[] = $this->captainResponse($captain);
        }
        return $finalCaptains;
    }

    public function mapOnTable($params){
        return [
            'captain_uuid'=>Str::uuid()->toString(),
            'user_id'=>$params['user_id'],
            'boat_id'=>$params['boat_id'],
            'name'=>$params['name'],
        ];
    }
}
