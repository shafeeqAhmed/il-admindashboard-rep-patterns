<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaLocation extends Model
{

    protected $guarded = [];

    /**
     * Get the owning imageable model.
     */
    public function locationable()
    {
        return $this->morphTo();
    }
}
