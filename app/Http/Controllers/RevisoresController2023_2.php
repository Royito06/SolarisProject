<?php

namespace App\Http\Controllers;

use App\Asistente;
use App\Revisores;
// use App\Asistente;
use Illuminate\Http\Request;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
class RevisoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:revisor');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('revisor.index');
    }

    public function listaEspera()
    {
        //$asistentes = Asistente::join('documentos', 'asistentes.id', '=', 'documentos.id_asistente')
        //->join('validaciones', 'documentos.id', '=', 'validaciones.id_documento')->where('estado', 'Espera')
        //->select('asistentes.id', 'asistentes.nombre', 'documentos.created_at')
        //->orderBy('created_at', 'asc')->get();
        //$asistentes_espera = $asistentes->where()
        // dd($asistentes);
        $asistentes = Asistente::all();

        return view('revisor.listaEspera', compact('asistentes'));
    }

    public function listaAsistentes()
    {
        // $asistentes = Asistente::with('documentos')->has('documentos')->get(); ASISNTENTE CON DOCUMENTOS


        // $asistentes = Asistente::join('documentos','asistentes.id', '=', 'documentos.id_asistente')
        // ->orderBy('nombre')->latest('documentos.created_at')->get();
        // collect($asistentes);
        // foreach ($asistentes as $key => $asistente) {
        //     if ($asistente->documentos) {
        //         $asistentes[$key] = $asistente;
        //     }
        // }
        // $asistentes = $asistentes->unique('nombre')->values()->all();
        // dd($asistentes);
        $asistentes = Asistente::all();
        
        
        return view('revisor.listaAsistentes',compact('asistentes'));
    }

    public function listaInscritos()
    {
        /**
         * 
         */
        // $asistentesInscritos = DB::table('asistentes')
        //     ->join('documentos', 'asistentes.id', '=', 'documentos.id_asistente')
        //     ->join('validaciones', function($join){
        //         $join->on('documentos.id', '=', 'validaciones.id_documento' )
        //                 ->where('validaciones.estado', '=', 'Aprobado');
        //     })
        //     ->join('concursos','asistentes.id', '=','concursos.id')
        //     ->select('asistentes.*', 'validaciones.estado','documentos.fecha_creacion','concursos.nombre as nombre_concurso')
        //     ->orderBy('nombre_concurso')->orderBy('asistentes.folio')
        //     ->get();
        $asistentesInscritos = Asistente::all();

        return view('revisor.listaInscritos', compact('asistentesInscritos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revisores  $revisores
     * @return \Illuminate\Http\Response
     */
    public function show(Revisores $revisores)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Revisores  $revisores
     * @return \Illuminate\Http\Response
     */
    public function edit(Revisores $revisores)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Revisores  $revisores
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revisores $revisores)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revisores  $revisores
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revisores $revisores)
    {
        //
    }

    public function wsGetUserData($id){
        
        $userData = Asistente::findOrFail($id);
        // dump($userData->documentos->validaciones->last());
        if ($userData->documentos()->exists()) {
            $userStatus = $userData->documentos->validaciones->last();
            $userCourses = $userData->concursos;
            // dump(gettype($userCourses));
            $data = array($userData, $userStatus,$userCourses);
        }else{
            $inscrito = false;
            $data = array($userData, $inscrito);
        }
        
        
        
        // $userPhoto= $userData->documentos->foto_asistente;
        // $userCertificate= $userData->documentos->acta_asistente;
        // $userSchool= $userData->documentos->constancia_escuela;
        
        
        return json_encode($data);
    }


}
