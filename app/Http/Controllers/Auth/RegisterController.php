<?php

namespace App\Http\Controllers\Auth;

use App\Asistente;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\Rule\Parameters;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'asistentes';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['required', 'string', 'max:255'],
            'correo_electronico' => ['required', 'string', 'max:255', 'unique:asistentes'],
            'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
/* 
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]); */
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Asistente
     */
    protected function create(array $data)
    {
        $data['confirmation_code'] = Str::random(25);

        Mail::send('vendor.notifications.email_validacion', $data, function($message) use ($data) {
            $message->to($data['correo_electronico'], $data['nombre'])->subject('CRFyM - Verifica tu dirección de correo electrónico');
        });

        //para probar los correos
        //dd("alto");
        
        return Asistente::create([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'],
            'correo_electronico' => $data['correo_electronico'],
            'contrasena' => Hash::make($data['contrasena']),
            'confirmation_code' => $data['confirmation_code'],
        ]);
/*         return Asistente::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]); */
    }

}
