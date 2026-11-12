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

    /**
     * Vistas del panel de asistentes que se refrescan solas (ver panelData()).
     */
    const VISTAS_PANEL = ['espera', 'asistentes', 'inscritos'];

    public function listaEspera()
    {
        return $this->renderPanel('espera', 'revisor.listaEspera');
    }

    public function listaAsistentes()
    {
        return $this->renderPanel('asistentes', 'revisor.listaAsistentes');
    }

    public function listaInscritos()
    {
        return $this->renderPanel('inscritos', 'revisor.listaInscritos');
    }

    /**
     * Endpoint JSON que consulta el panel cada pocos segundos (public/js/panel-tiempo-real.js).
     *
     * Recibe la "version" que el navegador ya tiene (?v=...). Si no cambió nada responde
     * solo {changed:false}; si cambió, devuelve el HTML de las filas ya renderizado con
     * la MISMA plantilla parcial que usa la carga inicial, así hay una sola fuente de verdad.
     */
    public function panelData(Request $request, $vista)
    {
        abort_unless(in_array($vista, self::VISTAS_PANEL, true), 404);

        $filas = $this->filasPanel($vista);
        $version = $this->versionPanel($filas);

        if ($request->query('v') === $version) {
            $respuesta = ['changed' => false, 'version' => $version];
        } else {
            $respuesta = [
                'changed' => true,
                'version' => $version,
                'total'   => $filas->count(),
                'html'    => view('revisor.partials.filas_' . $vista, ['filas' => $filas])->render(),
            ];
        }

        return response()->json($respuesta)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    private function renderPanel($vista, $plantilla)
    {
        $filas = $this->filasPanel($vista);

        return view($plantilla, [
            'filas'   => $filas,
            'version' => $this->versionPanel($filas),
        ]);
    }

    /**
     * Huella de los datos mostrados: si cambia cualquier dato o estatus, cambia la huella.
     */
    private function versionPanel($filas)
    {
        return md5($filas->toJson());
    }

    /**
     * Construye las filas de cada lista con 4 consultas en total (antes eran
     * varias consultas POR asistente) y calcula el estatus de forma segura:
     *   - sin documentos            -> null       ("Enviando")
     *   - con documentos sin validar -> 'Espera'
     *   - con validación            -> su estado (Espera / Rechazado / Aprobado)
     */
    private function filasPanel($vista)
    {
        $filas = Asistente::with(['asesor', 'concursos', 'documentosTodos.validaciones'])
            ->orderBy('id')
            ->get()
            ->map(function ($asistente) {
                $documento  = $asistente->documentosTodos->sortBy('id')->last();
                $validacion = $documento ? $documento->validaciones->sortBy('id')->last() : null;

                if (!$documento) {
                    $estado = null;
                } else {
                    $estado = ($validacion && $validacion->estado) ? $validacion->estado : 'Espera';
                }

                $fechaEnvio = ($documento && $documento->created_at) ? $documento->created_at : $asistente->created_at;

                return [
                    'id'              => $asistente->id,
                    'nombre'          => $asistente->nombre,
                    'nombre_completo' => trim(preg_replace('/\s+/', ' ', $asistente->nombre . ' ' . $asistente->apellido_paterno . ' ' . $asistente->apellido_materno)),
                    'folio'           => $asistente->folio,
                    'escuela'         => $asistente->escuela_origen,
                    'concursos'       => $asistente->concursos->pluck('nombre')->implode(', '),
                    'estado'          => $estado,
                    'asesor'          => $asistente->asesor ? $asistente->asesor->nombre : null,
                    'fecha_envio'     => $fechaEnvio ? $fechaEnvio->format('Y-m-d H:i:s') : null,
                ];
            });

        if ($vista === 'espera') {
            $filas = $filas->where('estado', 'Espera');
        } elseif ($vista === 'inscritos') {
            $filas = $filas->where('estado', 'Aprobado');
        }

        return $filas->values();
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

        $userData = Asistente::findOrFail($id);

        if ($userData->documentos()->exists()) {
            $userStatus = $userData->documentos->validaciones->sortBy('id')->last();
            // Documentos enviados pero sin registro de validación todavía = en revisión.
            if (!$userStatus) {
                $userStatus = ['estado' => 'Espera'];
            }
            $userCourses = $userData->concursos;
            $data = array($userData, $userStatus, $userCourses);
        }else{
            $inscrito = false;
            $data = array($userData, $inscrito);
        }

        // Sin caché para que el modal siempre muestre el estatus más reciente.
        return response()->json($data)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }


}
