<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Asistente extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use Notifiable;

    protected $table = 'asistentes';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 
        'apellido_paterno', 
        'apellido_materno' , 
        'correo_electronico', 
        'contrasena', 
        'confirmation_code', 
        'token',
        'token_created_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'contrasena', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword(){       
        return $this->contrasena;     
    }

    public function getAuthEmail(){       
        return $this->correo_electronico;     
    }

    public function asesor(){
        //return $this->belongsTo(Asesor::class);
        return $this->hasOne(Asesor::class, 'id_asistente');
    }

    public function concursos(){
        return $this->belongsToMany(Concursos::class,'asistente_concurso','id_asistente','id_concurso');
    }

    /**
     * linea comentada por si quieren que la relacion sea de 1 a muchos
     */
    // public function documentos(){
    //     return $this->hasMany(Documentos::class,'id_asistente');
    // }

    public function documentos(){
        //return $this->hasOne(Documentos::class,'id_asistente'); //retorna el primer elemento encontrado.
        return $this->hasOne(Documentos::class,'id_asistente')->latest()->limit(1);
    }
}
