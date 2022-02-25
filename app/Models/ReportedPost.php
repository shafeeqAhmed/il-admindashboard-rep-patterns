<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedPost extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function post() {
        return $this->belongsTo(BoatPost::class,'post_id','id');
    }
    public function user() {
        return $this->belongsTo(User::class,'reporter_id','id');
    }
    public function getReportedPost() {
        return $this->where('is_active',1)->with('post')->with('user')->orderBy('id','asc')->get();
    }
    public function updateReportedPost($column,$value,$data) {
        return $this->where($column,$value)->update($data);
    }

}
