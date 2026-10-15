<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    
    public function asistentes(){
        return $this->hasMany(Asistente::class);
    }
}
