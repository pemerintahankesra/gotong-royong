<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bantuan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bantuan';

    public function detil_bantuan(){
        return $this->hasMany(DetilBantuan::class);
    }

    public function donatur(){
        return $this->belongsTo(Donatur::class);
    }

    public function kelurahan(){
        return $this->belongsTo(Region::class, 'tagged_by', 'id');
    }

    public function program(){
        return $this->belongsTo(Program::class);
    }
}