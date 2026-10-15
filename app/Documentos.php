<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    //
    protected $fillable = [
        'foto_asistente', /*'acta_asistente',*/ 'constancia_escuela','fecha_creacion', 
    ];

    /**
     * funcion comentada por si se quiere tener la relacion uno a muchos
     */
    // public function asistente(){
    //     return $this->hasOne(Asistente::class);
    // }

    public function asistente(){
        return $this->belongsTo(Asistente::class);
    }

    public function validaciones(){
        return $this->hasMany(Validaciones::class,'id_documento');
    }
    
}
