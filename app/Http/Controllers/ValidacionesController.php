<?php

namespace App\Http\Controllers;

use App\Asistente;
use App\Documentos;
use App\Validaciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\String_;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ValidacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Validaciones  $validaciones
     * @return \Illuminate\Http\Response
     */
    public function show(Validaciones $validaciones)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Validaciones  $validaciones
     * @return \Illuminate\Http\Response
     */
    public function edit(Asistente $asistente)
    {
        //dd($asistente);
/*         $asistente = Asistente::where('asistentes.id', $asistente->id)->join('documentos', 'asistentes.id', '=', 'documentos.id_asistente')
        ->join('validaciones', 'documentos.id', '=', 'validaciones.id_documento')->get()->last();
 */
        $validacion = Asistente::where('asistentes.id', $asistente->id)->join('documentos','documentos.id_asistente','=','asistentes.id')->join('validaciones', 'documentos.id', '=', 'validaciones.id_documento')->get()->last();
        //dd($validacion);
        return view('revisor.validacion', compact('validacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Orden de operaciones (importante para el panel en tiempo real):
     *   1. Se calcula el nuevo estatus.
     *   2. Se GUARDA en base de datos (en una transacción) -> el panel lo refleja de inmediato.
     *   3. Hasta el final se envía el correo. Si el SMTP tarda o falla, el estatus ya quedó
     *      guardado y consistente (antes el estatus se guardaba DESPUÉS del correo).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Validaciones  $validaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validaciones $asistente)
    {
        // Solo Revisor y Administrador pueden validar
        if (Auth::guard('revisor')->user()->rol === 'Visualizador') {
            return redirect()->route('revisores.lista-espera')
                ->with('error', 'No tienes permisos para validar asistentes.');
        }

        $val = Validator::make($request->all(),[
            'fotoRechazo' => ['nullable', 'size:20'],
            'textoFotoRechazo' => ['exclude_if:fotoRechazo,null',],
            /*'actaRechazo' => ['nullable', 'size:18'],
            'textoActaRechazo' => ['exclude_if:actaRechazo,null'],*/
            'constanciaRechazo' => ['nullable', 'size:18'],
            'textoConstanciaRechazo' => ['exclude_if:constanciaRechazo,null'],
        ]);

        if ($val->fails())
        {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }

        $validacion = $asistente;
        $documento = Documentos::findOrFail($validacion->id_documento);
        $asistente = Asistente::findOrFail($documento->id_asistente);
        $fecha_actual = Carbon::now();
        $año_edicio_folio = $fecha_actual->year;
        $cursos_folio = [0,0,0];
        $cantidad_alumnos_aprobados = Validaciones::where('estado','Aprobado')->count() + 1;
        $alumnos_aprobados_folio = "".$cantidad_alumnos_aprobados;
        $fecha_actual = $fecha_actual->setTimezone('GMT-7')->format('d_M_Y , g:ia');

        $concursos = "";
        foreach ($asistente->concursos()->get() as $concursos_id) {
            $concursos .= $concursos_id->nombre . ", ";
        }

        if(!$request->fotoRechazo && /*!$request->actaRechazo &&*/ !$request->constanciaRechazo){

            $validacion->estado = "Aprobado";

            $asistente->fecha_registro_aceptado = $fecha_actual;

            foreach ($asistente->concursos()->get() as $curso_id) {
                $cursos_folio[$curso_id->id-1] = 1;
            };
            $alumnos_aprobados_folio = str_pad($alumnos_aprobados_folio, 3, 0, STR_PAD_LEFT);

            $folio = $año_edicio_folio."-".implode("", $cursos_folio)."-".$alumnos_aprobados_folio;

            $asistente->folio = $folio;

            $data = [
                "nombre" => $asistente->nombre,
                "apellido_paterno" => $asistente->apellido_paterno,
                "apellido_materno" => $asistente->apellido_materno,
                "correo_electronico" => $asistente->correo_electronico,
                "estado" => $validacion->estado,
                "concursos" => $concursos,
            ];
            $vistaCorreo = 'vendor.notifications.email_aprobado';
            $asuntoCorreo = 'CRFyM - Confirmación de inscripción';

        }else{

            $doc_rechazo = [$request->fotoRechazo, /*$request->actaRechazo,*/ $request->constanciaRechazo];
            $doc_rechazo = array_filter($doc_rechazo, function($v){return $v != null; });

            $comentario = [$request->textoFotoRechazo, /*$request->textoActaRechazo,*/ $request->textoConstanciaRechazo];
            $comentario = array_filter($comentario, function($v){return $v != null; });

            if(count($doc_rechazo) != count($comentario)){
                return redirect()->back()->withInput()->with('jsAlert', "Faltaron una o mas areas de texto por llenar");
            }
            $comentario = array_combine($doc_rechazo, $comentario);

            foreach ($comentario as $key => $value) {
                $comentario[$key] = "$key: $value";
            }

            $doc_rechazo = implode(", ", $doc_rechazo);
            $comentario = implode("\n", $comentario);

            $validacion->comentario = $comentario;
            $validacion->doc_rechazado = $doc_rechazo;
            $validacion->estado = "Rechazado";

            $data = [
                "nombre" => $asistente->nombre,
                "apellido_paterno" => $asistente->apellido_paterno,
                "apellido_materno" => $asistente->apellido_materno,
                "correo_electronico" => $asistente->correo_electronico,
                "estado" => $validacion->estado,
                "doc_rechazado" => $validacion->doc_rechazado,
                "comentario" => $validacion->comentario,
                "concursos" => $concursos,
            ];
            $vistaCorreo = 'vendor.notifications.email_rechazado';
            $asuntoCorreo = 'CRFyM - Inscripción pendiente';
        }

        $validacion->fecha_validacion = $fecha_actual;

        // 2. Guardado atómico: el folio del asistente y el estatus de la validación
        //    se guardan juntos o no se guarda ninguno.
        DB::transaction(function () use ($asistente, $validacion) {
            $asistente->save();   // sin cambios (rechazo) no ejecuta ninguna consulta
            $validacion->save();
        });

        // 3. El correo va al final: un SMTP lento o caído ya no retrasa ni corrompe el estatus.
        try {
            Mail::send($vistaCorreo, $data, function($message) use ($data, $asuntoCorreo) {
                $message->to($data['correo_electronico'], $data['nombre'])->bcc("adrianvo@mat.uson.mx")->subject($asuntoCorreo);
            });
        } catch (\Throwable $e) {
            Log::error('No se pudo enviar el correo de validación a '.$data['correo_electronico'].': '.$e->getMessage());

            return redirect()->route('revisores.lista-espera')
                ->with('warning', 'La evaluación de '.$data['nombre'].' se guardó correctamente, pero no se pudo enviar el correo a '.$data['correo_electronico'].'.');
        }

        return redirect()->route('revisores.lista-espera');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Validaciones  $validaciones
     * @return \Illuminate\Http\Response
     */
    public function destroy(Validaciones $validaciones)
    {
        //
    }
}
