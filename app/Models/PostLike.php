<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\BoatPost;

class PostLike extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function post(){
        return $this->belongsTo(BoatPost::class,'post_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function isPostLikeExist($post_id,$user_id) {
        return self::where(['post_id'=>$post_id,'user_id'=>$user_id])->exists();
    }

    public function getPostLikes($col, $val, $limit = null, $offset = null){
        return $this->where($col, $val)->where('is_active', 1)->with('user','post')->skip($offset)->take($limit)->get();
    }

}
