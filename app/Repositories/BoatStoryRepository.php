<?php

namespace App\Repositories;


use App\Models\BoatStories;
use App\Models\MediaLocation;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\CommonHelper;
use App\Traits\MediaUploadHelper;
use App\Traits\ThumbnailHelper;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatResponse;
use App\Traits\Responses\BoatStoryResponse;
use Illuminate\Support\Str;
//use Your Model

/**
 * Class BoatRepository.
 */
class BoatStoryRepository extends BaseRepository implements RepositoryInterface
{
    use BoatResponse;
    use BoatStoryResponse;
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
//            MediaUploadHelper::moveSingleS3Videos($inputs['media'], CommonHelper::$s3_image_paths['video_stories_image']);
//            ThumbnailHelper::processThumbnails($inputs['media'], 'video_stories');
            MediaUploadHelper::moveSingleS3Videos($inputs['media'], CommonHelper::$s3_image_paths['post_video']);
            ThumbnailHelper::processThumbnails($inputs['media'], 'post_video');
        }
        $story_inputs = self::mapOnTable($inputs);
        $story = $this->model->create($story_inputs);
        $this->processStoryLocation($inputs, $story);
        $boatStory = $this->model->getStoryDetail('story_uuid', $story->story_uuid);
        return $this->boatStoryResponse($boatStory);

    }


    public function processStoryLocation($inputs, $story) {
        if (!empty($inputs['address'])) {
            $location_inputs = $this->processLocationInputs($inputs, $story);
            $mediaLocationRepository = new MediaLocationRepository();
            $mediaLocationRepository->createMediaLocation($location_inputs);
        }
        return true;
    }

    public function processLocationInputs($location, $story) {
        $inputs = [];
        $inputs['location_uuid'] =  Str::uuid()->toString();
        $inputs['locationable_id'] =  (new StoryRepository())->getByColumn($story['story_uuid'],'story_uuid', ['id'])->id;
        $inputs['locationable_type'] =  'App\Models\BoatStories';
        $inputs['address'] = $location['address'];
        $inputs['lat'] = (!empty($location['lat'])) ? $location['lat'] : 0;
        $inputs['lng'] = (!empty($location['lng'])) ? $location['lng'] : 0;
        $inputs['street_number'] = (!empty($location['street_number'])) ? $location['street_number'] : "";
        $inputs['city'] = (!empty($location['city'])) ? $location['city'] : "";
        $inputs['country'] = (!empty($location['country'])) ? $location['country'] : "";
        $inputs['country_code'] = (!empty($location['country_code'])) ? $location['country_code'] : "";
        $inputs['zip_code'] = (!empty($location['zip_code'])) ? $location['zip_code'] : "";
//        $inputs['place_id'] = (!empty($location['place_id'])) ? $location['place_id'] : "";
        return $inputs;
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

    public function removeBoatStory($params){
        return $this->model->updateStory('story_uuid', $params['content_uuid'], ['is_active' => 0]);
    }

    public function makeMultiResponse($stories, $userId=false){
        $user_viewed = [];
        if($userId){
            $user_viewed = (new BoatStoriesViewedRepository())->getUserAllViewedStories($userId);
        }
        $final = [];
        foreach($stories as $story){
            $viewed = false;
            if(!empty($user_viewed)){
                $viewed = $user_viewed->first(function ($val) use($story){
                    return $val->story_id == $story['id'];
                });
                $viewed = (bool)$viewed;
            }
            $final[]= $this->boatStoryResponse($story, $viewed);
        }
        return $final;
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
