<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportedPosts extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function checkReportPost($inputs)
    {
        return self::where($inputs)->exists();

    }

}
