<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_turno';
    protected $fillable = ['nombre', 'hora_inicio', 'hora_fin'];

    public function calendarios()
    {
        return $this->hasMany(Calendario::class, 'id_turno');
    }
}
