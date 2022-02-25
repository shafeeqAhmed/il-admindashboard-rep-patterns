<?php

namespace App\Repositories;

use App\Models\ReportedPosts;
use App\Models\BoatPost;
use App\Models\ReportedPost;
use App\Repositories\RepositoryInterface\RepositoryInterface;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Support\Str;
use DB;



/**
 * Class ReportedPostRepository.
 */
class ReportedPostRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model() {
        return ReportedPost::class;
    }
    public function getReportedPost() {
        return $this->model->getReportedPost();      
    }
    public function updateReportedPost($params,$uuid) {
       if($params['type'] == 'blocked') {
          return $this->blockedPost($params,$uuid,0,1);
       }else {
           return $this->blockedPost($params,$uuid,1,0);

       }
    }
    public function blockedPost($params,$uuid,$reported_post_status,$block_status) {
        DB::beginTransaction();
        $post = $this->getByColumn($uuid,'reported_post_uuid')->first();
        $reported_post = $this->model->updateReportedPost('reported_post_uuid',$uuid,[ 'comments'=>$params['reason'],'is_active'=>$reported_post_status]);

        $boatPost = BoatPost::updateBoatPost('id',$post->post_id,['is_blocked'=>$block_status]);
        if($reported_post && $boatPost) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
    }
    public function unBlockedPost($params,$uuid) {
        DB::beginTransaction();
        $post = $this->getByColumn($uuid,'reported_post_uuid')->first();
        $reported_post = $this->model->updateReportedPost('reported_post_uuid',$uuid,[ 'comments'=>$params['reason'],'is_active'=>0]);

        $boatPost = BoatPost::updateBoatPost('id',$post->post_id,['is_blocked'=>1]);
        if($reported_post && $boatPost) {
            DB::commit();
            return true;
        }
        DB::rollBack();
        return false;
    }



    public function reportPost($params){
         $story_inputs = self::mapOnTable($params);
         $paramsforPost = self::paramsPost($params);

         $validateIfExist = $this->model->checkReportPost($paramsforPost);
         if($validateIfExist){
            return ['success' => true, 'message' => 'You have already reported this post.'];
         }
         $post = $this->model->create($story_inputs);
         if($post){
            return ['success' => true, 'message' => 'Post reported successfully'];
         }
         return ['success' => false, 'message' => 'Something went wrong.'];
    }


    public function mapOnTable($params){
        return [
            'reported_post_uuid' => Str::uuid()->toString(),
            'post_id'=> (new BoatPostRepository())->getByColumn($params['post_uuid'],'post_uuid',['id'])->id,
            'reporter_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
            'reported_type' => 'customer',
            'comments' => (!empty($params['comments'])) ? $params['comments'] : null,
        ];
    }
    public function paramsPost($params){
        return [
            'post_id'=> (new BoatPostRepository())->getByColumn($params['post_uuid'],'post_uuid',['id'])->id,
            'reporter_id'=> (new UserRepository())->getByColumn($params['user_uuid'],'user_uuid',['id'])->id,
        ];
    }
}
