<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Responses\Responses\ApiResponse;
use App\Repositories\BoatPostRepository;
use App\Repositories\ReportedPostRepository;
use Illuminate\Http\Request;
class BoatPostController extends Controller
{
    public $response;
    public $repository;
    public function __construct(ApiResponse $apiResponse, BoatPostRepository $repository)
    {
        $this->response = $apiResponse;
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type) {

        if($type == 'reported') {
            $records =  (new ReportedPostRepository())->getReportedPost();
        } else {
            $records =  $this->repository->getBlockedPosts();
        }
        return view('pages.post.index',compact('records','type'));

    }
    public function update(Request $request, $uuid)
    {
        $result = (new ReportedPostRepository())->updateReportedPost($request->all(),$uuid);
        if($result) {
            return $this->response->webResponse('Post updated Successfully!');
        }else {
            return $this->response->webResponse('There something going wrong please try again!',false);
        }
    }

}
