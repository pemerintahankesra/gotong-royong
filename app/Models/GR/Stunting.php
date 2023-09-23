<?php

namespace App\Models\GR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stunting extends Model
{
    use HasFactory;
    protected $connection = 'simprolamas', $table = 'balita_stunting';
}
