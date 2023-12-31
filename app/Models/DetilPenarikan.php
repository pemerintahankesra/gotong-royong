<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilPenarikan extends Model
{
    use HasFactory;

    protected $table = 'detil_penarikan';

    public function penarikan(){
        return $this->belongsTo(Penarikan::class, 'penarikan_id', 'id');
    }

    public function penerima(){
        return $this->belongsTo(Penerima::class, 'penerima_id', 'id');
    }
}
