<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    public function kelurahan(){
        return $this->hasMany(Region::class, 'sub_id', 'id');
    }

    public function kecamatan(){
        return $this->belongsTo(Region::class, 'sub_id', 'id');
    }
}
