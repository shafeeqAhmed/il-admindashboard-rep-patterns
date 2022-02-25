<?php

namespace App\Repositories;


use App\Models\BoatStories;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\ThumbnailHelper;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatResponse;
use App\Traits\Responses\BoatStoryResponse;
use Illuminate\Support\Str;
//use Your Model

/**
 * Class BoatRepository.
 */
class MediaContentRepository extends BaseRepository implements RepositoryInterface
{

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatStories::class;
    }

    public function createBoatStory($params){
        $boatId = (new BoatRepository())->getBoatIdByUuid($params['boat_uuid']);
        $params['boat_uuid']  = $boatId;
        return $this->processStory($params);
    }


    public function processStory($inputs) {
        if ($inputs['media_type'] == "image") {
            ThumbnailHelper::processThumbnails($inputs['media'], 'image_stories');
        } elseif ($inputs['media_type'] == "video") {
            ThumbnailHelper::processThumbnails($inputs['media'], 'video_stories');
        }
        $story_inputs = self::mapOnTable($inputs);
        $boat = $this->model->create($story_inputs);
        $boatStory = $this->model->getStoryDetail('story_uuid', $boat->story_uuid);
        return $this->boatStoryResponse($boatStory);
    }



    public function getBoatStories($inputs) {
        $boatId = (new BoatRepository())->getBoatIdByUuid($inputs['boat_uuid']);
        $stories = $this->model->getBoatStories('boat_id', $boatId);
        $data = [];
        foreach($stories as $story){
            $data[] = $this->boatStoryResponse($story);
        }
        return $data;
    }

    public function getSingleStory($inputs) {
        $story = $this->model->getStoryDetail('story_uuid', $inputs['story_uuid']);
        return $this->boatStoryResponse($story);
    }

    public function mapOnTable($params){
        return [
            'story_image' => ($params['media_type'] == "image") ? $params['media'] : null,
            'story_uuid' => Str::uuid()->toString(),
            'story_video' => ($params['media_type'] == "video") ? $params['media'] : null,
            'boat_id' => (!empty($params['boat_uuid'])) ? $params['boat_uuid'] : null,
            'video_thumbnail' => null,
            'text' => (!empty($params['text'])) ? $params['text'] : "",
            'url' => (!empty($params['url'])) ? $params['url'] : null,
        ];
    }

}
