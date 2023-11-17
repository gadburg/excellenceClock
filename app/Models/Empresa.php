<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empresa';
    protected $fillable = ['nombre', 'responsable'];

    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'responsable');
    }
}
