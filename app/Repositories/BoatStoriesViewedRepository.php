<?php

namespace App\Repositories;


use App\Models\BoatStoriesViewed;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatStoriesViewedResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class BoatStoriesViewedRepository extends BaseRepository implements RepositoryInterface
{
 use BoatStoriesViewedResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatStoriesViewed::class;
    }

    public function addStoryView($params){
        $data = $this->mapOnTable($params);
        $records = $this->model->where('user_id', $data['user_id'])->where('story_id', $data['story_id'])->first();
        if(!$records){
            $this->model->create($this->mapOnTable($params));
        }
    }

    public function getUserRecentViewedStories($params){
        $userId = (new UserRepository())->getByColumn($params['user_uuid'], 'user_uuid', ['id'])->id;
        return $this->model->where('user_id', $userId)
            ->whereBetween('created_at', [now()->subMinutes(1440), now()])
            ->where('is_active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUserAllViewedStories($userId){
        return $this->model->where('user_id', $userId)
            ->where('is_active', '=', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function mapOnTable($params){
        return [
            'stories_viewed_uuid' => Str::uuid()->toString(),
            'story_id'=> (new BoatStoryRepository())->getByColumn($params['story_uuid'],'story_uuid',['id'])->id,
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
        ];
    }

}
