<?php

namespace App\Repositories;


use App\Events\BoatRatingEvent;
use App\Models\Boat;
use App\Models\BoatReview;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatReviewResponse;

//use Your Model

/**
 * Class BoatRepository.
 */
class BoatReviewRepository extends BaseRepository implements RepositoryInterface
{
 use BoatReviewResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatReview::class;
    }

    public function createBoatReview($params){
        $input = $this->mapOnTable($params);
//        if(!$this->model->isBoatReviewExist($input['boat_id'],$input['booking_id'],$input['user_id'])) {
            $review = $this->create($input);
            event(new BoatRatingEvent($review->toArray()));
            return $this->boatReviewResponse($review);
//        }
        return [];
    }
    public function createBoatReviewReply($params){
        if(!$this->model::isReplied($params['review_uuid'])) {
            $this->model->createBoatReviewReply('review_uuid',$params['review_uuid'],['reply'=>$params['reply']]);
            return  $this->boatReviewResponse($this->getByColumn($params['review_uuid'],'review_uuid'));
        }
        return [];
    }
    public function makeMultiResponse($reviews){
        $final = [];
        foreach($reviews as $review){
            $final[]= $this->boatReviewResponse($review);
        }
        return $final;
    }
    public function mapOnTable($params){
        return [
            'review_uuid'=>Str::uuid()->toString(),
            'boat_id'=> (new BoatRepository)->getByColumn($params['boat_uuid'],'boat_uuid',['id'])->id,
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
            'booking_id'=> (new BookingRepository())->getByColumn($params['booking_uuid'],'booking_uuid',['id'])->id,
            'rating'=>$params['rating'],
            'review'=>$params['review']
        ];
    }

}
