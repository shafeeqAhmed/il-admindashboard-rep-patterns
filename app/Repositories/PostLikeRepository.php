<?php

namespace App\Repositories;


use App\Events\PostLikeEvent;
use App\Models\PostLike;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use App\Traits\Responses\PostLikeResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BoatRepository.
 */
class PostLikeRepository extends BaseRepository implements RepositoryInterface
{
    use PostLikeResponse;
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return PostLike::class;
    }
    public function addPostLike($params){
        if($params['type'] == 'favorite') {
            $this->favorite($params);
        }
        if($params['type'] == 'unfavorite') {
            $this->unfavourite($params);
        }

        $postRepository = new BoatPostRepository();

        return ($postRepository->getPostDetail($params));
    }

    public function getPostLikes($params){
        $postRepository = new BoatPostRepository();
        $postId = $postRepository->getByColumn($params['post_uuid'], 'post_uuid',['id'])->id;
        return $this->makeMultiResponse($this->model->getPostLikes('post_id', $postId, ($params['limit']), $params['offset']));
    }

    public function makeMultiResponse($boatPosts){
        $final = [];
        foreach($boatPosts as $post){
            $final[] = $this->postLikeResponse($post);
        }

        return $final;
    }

    public function checkPostLiked($userId, $postId){
        return $this->model->isPostLikeExist($postId, $userId);
    }

    public function favorite($params) {
        $input = $this->mapOnTable($params);
//        if(!$this->model->isPostLikeExist($input['post_id'],$input['user_id'])) {
            $post = $this->create($input);
            event(new PostLikeEvent($post->toArray()));
            return $this->postLikeResponse($post);
//        }
        return [];
    }

/*    public function getFavouriteBoatsByUser($user_id){
        return $this->model->getFavouriteBoatsCount($user_id);
    }*/

    public function unfavourite($params) {
        $input = $this->mapOnTable($params);
        if($this->model->isPostLikeExist($input['post_id'],$input['user_id'])) {
            $this->where('post_id',$input['post_id'])->where('user_id',$input['user_id'])->delete();
        }
        return [];
    }

//    public function getUserFavouriteBoats($params){
//        $final = [];
//        $userId = User::where('user_uuid',$params['user_uuid'])->value('id');
//        $favourites = $this->model->getFavouriteBoatsByUser($userId);
//        if(!empty($favourites)){
//            foreach($favourites as $boat){
//                $final[]  = (new BoatRepository())->boatResponse($boat['boat']);
//            }
//        }
//        return $final;
//    }

    public function mapOnTable($params){
        return [
            'like_uuid' => Str::uuid()->toString(),
            'post_id'=> (new BoatPostRepository())->getByColumn($params['post_uuid'],'post_uuid',['id'])->id,
            'user_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
        ];
    }

}
