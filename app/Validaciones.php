<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Validaciones extends Model
{
    protected $table = 'validaciones';

    protected $fillable = [
        'id_documento',
        'id_revisor',
        'comentario',
        'doc_aprobado',
        'doc_rechazado',
        'estado',
        'fecha_validacion',
    ];
    
    public function documento(){
        return $this->belongsTo(Documentos::class, 'id_documento');
    }

    public function revisor(){
        return $this->belongsTo(Revisores::class, 'id_revisor');
    }
}
