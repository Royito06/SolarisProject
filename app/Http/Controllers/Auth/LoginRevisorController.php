<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginRevisorController extends Controller
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
    protected $redirectTo = 'revisores';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:revisor')->except('logout');
    }


    public function showLoginForm()
    {
        return view('revisor.login');
    }

    public function login(Request $request)
    {
        //dd($request);
        //$credentials = $request->only('correo', 'contrasena');
        $val = Validator::make($request->all(),[
            'correo' => ['required', 'exists:revisores'],
            'contrasena' => ['required', 'min:6'],
        ]);

        //dd($val);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }else{
            if (Auth::guard('revisor')->attempt(['correo' => $request->get('correo'), 'password' => $request->get('contrasena')])) {
                return redirect()->intended('revisores');
            }else{
                return back()->withErrors(['contrasena' => trans('auth.password')])->withInput(request(['contrasena']));
            }
        }
 /*        if (Auth::guard('revisor')->attempt(['correo' => $request->get('correo'), 'password' => $request->get('contrasena')])) {
            // Authentication passed...
            return redirect()->intended('revisores');
        } */
        //return back();
    }

    public function logout(Request $request)
    {
        Auth::guard('revisor')->logout();
        return redirect('admin');
    }

    protected function guard()
    {
        return Auth::guard('revisor');
    }
} 
