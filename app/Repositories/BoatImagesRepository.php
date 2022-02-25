<?php

namespace App\Repositories;


use App\Models\BoatImage;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use App\Traits\Responses\BoatImagesResponse;
//use Your Model

/**
 * Class BoatRepository.
 */
class BoatImagesRepository extends BaseRepository implements RepositoryInterface
{
 use BoatImagesResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatImage::class;
    }

    public function createMultipleImagesObject($images, $boat_id){
        $response = [];
        foreach($images as $image){
            $response[] = [
                'boat_image_uuid' => Str::uuid()->toString(),
                'boat_id' => $boat_id,
                'url' => $image
            ];
        }
        return $response;
    }

    public function removeImage($params){
        return $this->model->updateImage('boat_image_uuid', $params['image_uuid'], ['is_active' => 0]);
    }

    public function mapOnTable($params){
        return [

        ];
    }

}
