<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'donatur';

    public function kelurahan(){
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}
