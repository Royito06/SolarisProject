<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Validaciones extends Model
{
    protected $fillable = [
        'id_documento', 'id_revisor', 
    ];
    
    public function documento(){
        return $this->belongsTo(Documentos::class, 'id_documento');
    }

}
