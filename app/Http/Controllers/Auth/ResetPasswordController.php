<?php

namespace App\Http\Controllers\Auth;

use App\Asistente;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Carbon\Carbon;
use DateTime;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm($token){
        $user = Asistente::where('token', $token)->first();
        
        if (!$user) return redirect('/')->with('notification_mal', 'El link que haz utilizado expiró o ya se usó anteriormente');
        
        $fecha_actual = new DateTime();
        $token_created_at = new DateTime($user->token_created_at);
        
        if($fecha_actual->diff($token_created_at)->days > 1){
            $user->token = null;
            $user->token_created_at = null;
            $user->save();
            return redirect('/')->with('notification_mal', 'El link que haz utilizado expiró o ya se uso anteriormente');
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'correo_electronico' => $user->correo_electronico]
        );
    }

    public function reset(Request $request){

        $val = Validator::make($request->all(),[
            'correo_electronico' => ['required', 'string', 'exists:asistentes'],
            'contrasena' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => 'required',
        ]);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }

        $user = Asistente::where('correo_electronico', $request->correo_electronico)->where('token', $request->token)->first();
        if (!$user) {
            return  back()->with('notification', 'El correo que usted ingresó no pidió un cambio de contraseña');
        }

        $user->token = null;
        $user->contrasena = Hash::make($request->contrasena);
        $user->save();

        return redirect('/')->with('notification', '¡Su contraseña ha sido restablecida!');
    }
}
