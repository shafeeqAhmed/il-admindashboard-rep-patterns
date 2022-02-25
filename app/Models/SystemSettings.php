<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class SystemSettings extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function systemSettings() {
        $query = $this->where('is_active',1)->first();
        return ($query) ? $query->toArray() : null;
    }

    public function systemSettingActive(){
        $query = $this->where('is_active', 1)->orderBy('id', 'desc')->first();
        return ($query) ? $query->toArray() : null;
    }
    public function addSettings($data) {
        DB::beginTransaction();
        $this->where('is_active',1)->update(['is_active'=>0]);

        $query= $this->create($data);
        $query ? DB::commit() : DB::rollBack();

        return ($query) ? $query->toArray() : null;

    }
}
