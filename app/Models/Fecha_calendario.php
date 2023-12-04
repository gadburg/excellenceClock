<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fecha_calendario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = ['fecha'];
}
