<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatStoriesViewed extends Model
{
    use HasFactory;

    protected $table = 'stories_viewed';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function story(){
        return $this->belongsTo(BoatStories::class,'story_id','id');
    }
}
