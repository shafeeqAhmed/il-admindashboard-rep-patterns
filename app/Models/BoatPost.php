<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatPost extends Model
{
    use HasFactory;

    protected $guareded = ['id'];
    protected $fillable = ['src', 'post_uuid', 'boat_id', 'caption', 'media_type'];

    public function boat(){
        return $this->belongsTo(Boat::class,'boat_id','id');
    }

    public function location(){
        return $this->morphOne('App\Models\MediaLocation', 'locationable');
    }

    public function updatePost($col, $val, $data){
        return $this->where($col, $val)->update($data);
    }

    public function users(){
        return $this->hasMany(User::class, 'user_id', 'id');
    }

    public function likes(){
        return $this->hasMany(PostLike::class, 'post_id', 'id');
    }



    public function getPostDetail($column, $value){

        return $this->where($column, '=', $value)
            ->with('boat')
            ->with('location')
            ->withCount('likes')
            ->first()->toArray();
    }
    public function getAdminBlockedPosts() {
        return $this->where('is_blocked',1)->with('boat')->with('reportedRecord')->orderBy('id','asc')->get();
    }
    public function updateBoatPost($column,$value,$data) {
        return self::where($column,$value)->update($data);
    }

}
