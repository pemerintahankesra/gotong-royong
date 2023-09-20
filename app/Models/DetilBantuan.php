<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilBantuan extends Model
{
    use HasFactory;
    protected $table = 'detil_bantuan';

    public function bantuan(){
        return $this->belongsTo(Bantuan::class);
    }
}