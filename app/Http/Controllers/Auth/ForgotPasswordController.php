<?php

namespace App\Http\Controllers\Auth;

use App\Asistente;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
//use Carbon\Carbon;
use DateTime;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $val = Validator::make($request->all(),[
            'correo_electronico' => ['required', 'exists:asistentes'],
        ]);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }

        $registro = Asistente::where('correo_electronico', $request->correo_electronico)->first();
        if ($registro->token) {
            $registro->token = null;
            $registro->token_created_at = null;
            $registro->save();
        }
        
        $token = Str::random(30);
        
        $registro->token = $token;
        $registro->token_created_at = new DateTime();
        $registro->save();

        $arry_registro = ["nombre" => $registro->nombre, "apellido_paterno" => $registro->apellido_paterno, "apellido_materno" => $registro->apellido_materno, "correo_electronico" => $registro->correo_electronico, "token" => $registro->token];
        //dd(redirect()->getUrlGenerator()->previous());

        Mail::send('vendor.notifications.email_reset', $arry_registro, function($message) use ($arry_registro) {
            $message->to($arry_registro['correo_electronico'], $arry_registro['nombre'])->subject('CRFyM - Reset de Contraseña');
        });
        
        //dd("alto");
        return redirect()->back()->with('status', '¡Le hemos enviado por correo electrónico el enlace para restablecer su contraseña!');
    }
}
