<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Revisores extends Authenticatable
{
    use Notifiable;

    protected $guard = 'revisor';
    protected $table = 'revisores';
    protected $primaryKey = 'id';

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'contrasena', 'rol',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'contrasena', 'remember_token',
    ];

    public function getAuthPassword(){       
        return $this->contrasena;     
    }
    
    public function validaciones(){
        return $this->hasMany(Validaciones::class, 'id_revisor');
    }
}
