<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/*
class Asesor extends Model
{
    
    public function asistentes(){
        return $this->hasMany(Asistente::class);
    }
}
*/

class Asesor extends Model
{
    protected $table = 'asesores';
    protected $fillable = [
        'id_asistente',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo_electronico',
        'telefono',
        'escuela_origen'
    ];
    public function asistente()
    {
        return $this->belongsTo(Asistente::class, 'id_asistente');
    }
}