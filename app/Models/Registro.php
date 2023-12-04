<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_usuario', 'fecha', 'rango', 'ubicacion', 'ip', 'total'
    ];

    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
