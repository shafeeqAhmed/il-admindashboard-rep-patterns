<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoatDefaultService extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function getDefaultServices() {
        $query = $this->where('is_active',1)->get();
        return $query ? $query->toArray() : [];
    }
}
