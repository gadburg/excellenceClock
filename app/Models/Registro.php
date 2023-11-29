<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_registro';
    protected $fillable = [
        'user_id', 'fecha', 'rango', 'ubicacion', 'ip', 'total'
    ];

    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
