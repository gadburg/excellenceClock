<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = ['id_turno', 'id_usuario'];

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
