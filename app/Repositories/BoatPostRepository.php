<?php

namespace App\Repositories;

use App\Models\BoatPost;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\CommonHelper;
use App\Traits\MediaUploadHelper;
use App\Traits\Responses\PostResponse;
use App\Traits\ThumbnailHelper;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoatPostRepository.
 */
class BoatPostRepository extends BaseRepository implements RepositoryInterface
{
    use PostResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return BoatPost::class;
    }

    public function makeMultiResponse($boatPosts){
       $final = [];
        foreach($boatPosts as $post){
            $final[] = $this->postResponse($post);
       }

        return $final;
    }


    public function createBoatPost($params){
        $boatId = (new BoatRepository())->getBoatIdByUuid($params['boat_uuid']);
        $params['boat_uuid']  = $boatId;
        return $this->processPost($params);
    }

    public function getPostDetail($params){
        $userId = null;
        if(isset($params['user_uuid'])){
            $userId = (new UserRepository())->getByColumn($params['user_uuid'], 'user_uuid', ['id'])->id;
        }
        return $this->postResponse($this->model->getPostDetail('post_uuid', $params['post_uuid']), $userId);
    }

    public function processPost($inputs) {
        if ($inputs['media_type'] == "image") {
            ThumbnailHelper::processThumbnails($inputs['media'], 'post_image');
        } elseif ($inputs['media_type'] == "video") {
            MediaUploadHelper::moveSingleS3Videos($inputs['media'], CommonHelper::$s3_image_paths['post_video']);
            ThumbnailHelper::processThumbnails($inputs['media'], 'post_video');
        }
        $story_inputs = self::mapOnTable($inputs);
        $post = $this->model->create($story_inputs);
        $this->processPostLocation($inputs, $post);
        $userId = null;
        if(isset($inputs['user_uuid'])){
            $userId = (new UserRepository())->getByColumn($inputs['user_uuid'], 'user_uuid')->value('id');
        }
        $boatPost = $this->model->getPostDetail('post_uuid', $post->post_uuid, $userId);
        return $this->postResponse($boatPost, $userId);
    }

    public function removeBoatPost($params){
        return $this->model->updatePost('post_uuid', $params['content_uuid'], ['is_active' => 0]);
    }

    public function processPostLocation($inputs, $post) {
        if (!empty($inputs['address'])) {
            $location_inputs = $this->processLocationInputs($inputs, $post);
            $mediaLocationRepository = new MediaLocationRepository();
            $mediaLocationRepository->createMediaLocation($location_inputs);
        }
        return true;
    }
    public function getBlockedPosts() {
        return  $this->model->getAdminBlockedPosts();
    }

    public function processLocationInputs($location, $post) {
        $inputs = [];
        $inputs['location_uuid'] =  Str::uuid()->toString();
        $inputs['locationable_id'] =  (new BoatPostRepository())->getByColumn($post['post_uuid'],'post_uuid', ['id'])->id;
        $inputs['locationable_type'] =  'App\Models\BoatPost';
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


    public function mapOnTable($params){
        return [
            'src' => $params['media'],
            'post_uuid' => Str::uuid()->toString(),
            'caption' => (!empty($params['caption'])) ? $params['caption'] : "",
            'boat_id' => (!empty($params['boat_uuid'])) ? $params['boat_uuid'] : null,
            'text' => (!empty($params['text'])) ? $params['text'] : "",
            'url' => (!empty($params['url'])) ? $params['url'] : null,
            'media_type' =>$params['media_type'],
        ];
    }
}
