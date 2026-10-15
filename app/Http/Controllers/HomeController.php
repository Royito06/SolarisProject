<?php

namespace App\Http\Controllers;

use App\Asistente;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function verify($code)
    {
        $user = Asistente::where('confirmation_code', $code)->first();
        
        if (!$user) return redirect('/')->with('notification_mal', 'El link que haz utilizado expiró o ya se uso anteriormente');
    
        $user->email_verified_at = Carbon::now()->setTimezone('GMT-7')->format('Y-m-d h:i:s');
        //dd($user->email_verified_at);
        //dd("alto");
        $user->confirmation_code = null;
        $user->save();
    
        return redirect('/')->with('notification', 'Has confirmado correctamente tu correo!');
    }
}
