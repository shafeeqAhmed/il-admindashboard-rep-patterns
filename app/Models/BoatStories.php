<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatStories extends Model
{
    use HasFactory;
    protected $fillable = ['story_image', 'story_uuid', 'boat_id', 'story_video'];


    public function boat(){
        return $this->belongsTo(Boat::class,'boat_id','id');
    }

    public function location(){
        return $this->morphOne('App\Models\MediaLocation', 'locationable');
    }

    public function getBoatStories($column, $value) {
        $result = $this->where($column, '=', $value)
            ->where('is_active', '=', 1)
            ->whereBetween('created_at', [now()->subMinutes(1440), now()])
            ->orderBy('created_at', 'DESC')
            ->with('boat')
            ->get();
        return !empty($result) ? $result->toArray() : [];
    }

    public function updateStory($col, $val, $data){
        return $this->where($col, $val)->update($data);
    }

    public function getStoryDetail($column, $value){
        return $this->where($column, '=', $value)
            ->with('boat')
            ->with('location')
            ->first()->toArray();
    }
}
