<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
//use Dotenv\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        //dd($request->all());
        $val = Validator::make($request->all(),[
            'correo_electronico' => ['required', 'exists:asistentes'],
            'contrasena' => ['required', 'min:6'],
        ]);

        //dd($val);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }else{
            if (Auth::attempt(['correo_electronico' => $request->get('correo_electronico'), 'password' => $request->get('contrasena')])) {
                if (Auth::attempt(['correo_electronico' => $request->get('correo_electronico'), 'password' => $request->get('contrasena')])) {
                    return redirect()->intended('/asistentes');
                }
            }else{
                return back()->withErrors(['contrasena' => trans('auth.password')])->withInput(request(['contrasena']));
            }
        }

/*         if (Auth::attempt(['correo_electronico' => $request->get('correo_electronico'), 'password' => $request->get('contrasena')])) {
            // Authentication passed...
            return redirect()->intended('/');
        } */



        //return back()->withErrors(['correo_electronico' => trans('auth.failed')])->withInput(request(['correo_electronico']));
    }
} 
