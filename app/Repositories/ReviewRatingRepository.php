<?php

namespace App\Repositories;

use App\Models\BoatReview;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class ReviewRatingRepository.
 */
class ReviewRatingRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatReview::class;
    }

    public function getBoatRating($boatId){
        return $this->model->getBoatRating($boatId);
    }

    public function mapOnTable($params){
        return [];
    }
}
