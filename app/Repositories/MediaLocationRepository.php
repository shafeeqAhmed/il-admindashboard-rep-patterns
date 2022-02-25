<?php

namespace App\Repositories;


use App\Models\BoatStories;
use App\Models\MediaLocation;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\ThumbnailHelper;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatResponse;
use App\Traits\Responses\BoatStoryResponse;
use App\Traits\Responses\MediaLocationResponse;
use Illuminate\Support\Str;
//use Your Model

/**
 * Class BoatRepository.
 */
class MediaLocationRepository extends BaseRepository implements RepositoryInterface
{

    use MediaLocationResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return MediaLocation::class;
    }

    public function createMediaLocation($params){
        return $this->create($params);
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
