<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penarikan';

    public function detil_penarikan() {
        return $this->hasMany(DetilPenarikan::class, 'penarikan_id', 'id');
    }

    public function region(){
        return $this->belongsTo(Region::class, 'tagged_by', 'id');
    }

    public function program(){
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
