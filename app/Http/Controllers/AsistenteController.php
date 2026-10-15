<?php

namespace App\Http\Controllers;

use App\Asistente;
use App\Concursos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\DocumentosController;
use DB;
use Illuminate\Support\Facades\Validator;

class AsistenteController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web', 'verified']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('asistente.perfil');
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
     * @param  \App\Asistente  $asistente
     * @return \Illuminate\Http\Response
     */
    public function show(Asistente $asistente)
    {
        return view('asistente.perfil');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Asistente  $asistente
     * @return \Illuminate\Http\Response
     */
    public function edit(Asistente $asistente)
    {
        return view('asistente.formInscripcion', compact('asistente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Asistente  $asistente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Asistente $asistente)
    {
        //
        //dd($request);

        $val = Validator::make($request->all(),[
            'curp' => ['required', 'regex:/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/'],
            'fecha_nacimiento' => ['required', 'date_format:Y-m-d'],
            'escuela_origen' => ['required', ],
            'telefono' => ['required', 'digits:10',],
            'concurso.*' => ['required', 'digits_between:1,3'],
            'image_croped' => ['required', 'min:5000'],
            'foto_asistente' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg'],
            'constancia_escuela' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg'],
        ]);

        //dd($val);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }  
        
        $idConcursos= $request->input('concurso');
        $documentos = new DocumentosController();
        
        $concursos = Concursos::findOrFail($idConcursos);

        $asistente->curp = $request->input('curp');
        
        $asistente->fecha_nacimiento = $request->input('fecha_nacimiento');
        $asistente->escuela_origen = $request->input('escuela_origen');
        $asistente->telefono = $request->input('telefono');
        
        $asistente->concursos()->attach($concursos);        
       
        $asistente->save();
        /**
         * DOCUMENTOS
         * se llama a la funcion store del controlador DocumentosController.
         */

        $documentos->store($request,$asistente);
        //============

    
        // $asistente->save();
        return redirect()->route('asistentes.show', compact('asistente'));
        
    }
    

    public function indexRechazados(){
        
        $cabecera = array();
        $estadosDocumentos = DB::table('documentos')
            ->where('documentos.id_asistente', '=' ,auth()->user()->id)
            ->join('validaciones', 'validaciones.id_documento', '=' , 'documentos.id')
            ->latest('validaciones.updated_at')->first();
        // dd(gettype($estadosDocumentos));
        $cabecera['documentos_rechazados'] = explode(',',$estadosDocumentos->doc_rechazado);
        $cabecera['documentos_rechazados'] = array_map('trim',$cabecera['documentos_rechazados']);
        // dd($cabecera['estados_documentos']);
        return view('asistente.editarDocumentos', compact('cabecera'));

        
        // return view('asistente.editarDocumentos');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Asistente  $asistente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Asistente $asistente)
    {
        //
    }
}
