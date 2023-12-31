<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donatur extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'donatur';

    public function kelurahan(){
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
