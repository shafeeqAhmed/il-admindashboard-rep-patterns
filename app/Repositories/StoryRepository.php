<?php

namespace App\Repositories;

use App\Models\BoatStories;
use App\Models\Boat;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

class StoryRepository extends BaseRepository implements RepositoryInterface {

    use \App\Traits\Responses\StoryResponse;

    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return BoatStories::class;
    }

    public function getStories($data) {
        $stories = Boat::getStories('is_active', 1);
        $user_viewed = [];
        if(isset($data['user_uuid'])){
            $user_viewed = (new BoatStoriesViewedRepository())->getUserRecentViewedStories($data);
        }
        $response = $this->storyResponse($stories, $user_viewed);
        return $response;
    }

    public function mapOnTable($param) {

    }

}
