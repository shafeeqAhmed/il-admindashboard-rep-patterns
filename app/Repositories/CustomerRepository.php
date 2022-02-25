<?php

namespace App\Repositories;


use App\Models\User;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\CustomerResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class CustomerRepository extends BaseRepository implements RepositoryInterface
{
 use CustomerResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function mapOnTable($params){
        return [

        ];
    }

}