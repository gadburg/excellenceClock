<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_registro';
    protected $fillable = ['id_usuario', 'fecha', 'hora_salida', 'hora_entrada', 'longitud', 'latitud', 'ip'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
