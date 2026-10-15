<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concursos extends Model
{
    //
    public function asistentes(){
        return $this->belongsToMany('App\Asistente', 'asistente_concurso','id_concurso','id_asistente');
    }
}
