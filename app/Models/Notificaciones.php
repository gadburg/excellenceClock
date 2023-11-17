<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificaciones extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_notificacion';
    protected $fillable = ['mensaje', 'id_usuario', 'tipo', 'creador'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creador');
    }
}
