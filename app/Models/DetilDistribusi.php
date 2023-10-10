<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetilDistribusi extends Model
{
    use HasFactory;
    protected $table = 'detil_distribusi';

    public function distribusi(){
        return $this->belongsTo(Distribusi::class);
    }
}
