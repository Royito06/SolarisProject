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
        $revisores = Revisores::orderBy('id', 'desc')->get();
        return view('revisor.index', compact('revisores'));
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
        // 1. Validamos los campos
        $request->validate([
            'archivo_correos' => 'nullable|file|mimes:txt,csv|max:2048',
            'correos_manual'  => 'nullable|string',
            'rol'             => 'required|in:Revisor,Visualizador,Administrador',
        ], [
            'archivo_correos.mimes' => 'El archivo debe ser de formato .txt o .csv',
            'rol.required'          => 'Debes seleccionar un rol para los revisores.',
            'rol.in'                => 'El rol seleccionado no es válido.',
        ]);

        $rawText = '';

        // Si se subió un archivo .txt o .csv, leemos su contenido
        if ($request->hasFile('archivo_correos')) {
            $rawText .= file_get_contents($request->file('archivo_correos')->getRealPath()) . "\n";
        }

        // Si se escribieron correos manualmente, los concatenamos
        if (!empty(trim($request->correos_manual))) {
            $rawText .= " " . $request->correos_manual;
        }

        if (empty(trim($rawText))) {
            return redirect()->back()->with('error', 'Debes adjuntar un archivo (.txt o .csv) o ingresar al menos un correo de forma manual.');
        }

        // Separamos los correos por comas, punto y coma, saltos de línea o espacios
        $correosArray = preg_split('/[\s,;]+/', $rawText, -1, PREG_SPLIT_NO_EMPTY);

        $nuevos = 0;
        $actualizados = 0;
        $invalidos = [];

        foreach ($correosArray as $correoItem) {
            $correoLimpio = strtolower(trim($correoItem));

            // Validamos que tenga estructura de correo electrónico válida
            if (filter_var($correoLimpio, FILTER_VALIDATE_EMAIL)) {
                $partes = explode('@', $correoLimpio);
                $nombreBase = ucfirst(str_replace(['.', '_', '-'], ' ', $partes[0]));

                $revisor = Revisores::firstOrNew(['correo' => $correoLimpio]);

                if (!$revisor->exists) {
                    $revisor->nombre = $nombreBase ?: 'Revisor';
                    $revisor->apellido_paterno = 'Designado';
                    $revisor->apellido_materno = 'Comité';
                    $revisor->contrasena = bcrypt('Concurso2026!');
                    $revisor->rol = $request->rol;
                    $revisor->save();
                    $nuevos++;
                } else {
                    // Si ya existe, actualizamos su rol
                    $revisor->rol = $request->rol;
                    $revisor->save();
                    $actualizados++;
                }
            } else {
                $invalidos[] = $correoItem;
            }
        }

        $mensaje = "¡Operación completada! Se registraron {$nuevos} nuevo(s) revisor(es)";
        if ($actualizados > 0) {
            $mensaje .= " y se actualizaron {$actualizados} existente(s)";
        }
        $mensaje .= " con el rol '{$request->rol}'. La contraseña temporal asignada es: Concurso2026!";

        if (count($invalidos) > 0) {
            $mensaje .= " (Correos no válidos ignorados: " . implode(', ', array_slice($invalidos, 0, 5)) . (count($invalidos) > 5 ? '...' : '') . ")";
        }

        return redirect()->route('revisores.index')->with('success', $mensaje);
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
        
        //$userData = Asistente::findOrFail($id); Lo comente porque en la tarjeta de info no carga los documentos
        $userData = Asistente::with('documentos')->findOrFail($id); //antigravity me dio esta linea en su lugar
        // dump($userData->documentos->validaciones->last());
        if ($userData->documentos()->exists()) {
            $userStatus = $userData->documentos->validaciones->last();
            //$userCourses = $userData->concursos; 
            $userCourses = $userData->concursos->unique('id')->values(); //Esto filtra los concursos para mostrar solo los únicos sin importar si en la base de datos hay duplicados
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
