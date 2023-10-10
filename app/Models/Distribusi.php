<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distribusi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'distribusi';

    public function detil_distribusi(){
        return $this->hasMany(DetilDistribusi::class);
    }

    public function penerima(){
        return $this->belongsTo(Penerima::class);
    }

    public function tagged(){
        return $this->belongsTo(User::class, 'tagged_by', 'id');
    }
}