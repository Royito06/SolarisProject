<?php

namespace App\Http\Controllers;

use App\Asistente;
use App\Documentos;
use App\Validaciones;
use App\Mail\NotificacionValidacionAprobada;
use App\Mail\NotificacionValidacionRechazada;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ValidacionesController extends Controller
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
        return redirect()->route('revisores.lista-espera');
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
     * @param  \App\Asistente  $asistente
     * @return \Illuminate\Http\Response
     */
    public function edit(Asistente $asistente)
    {
        $validacion = Asistente::where('asistentes.id', $asistente->id)
            ->join('documentos', 'documentos.id_asistente', '=', 'asistentes.id')
            ->join('validaciones', 'documentos.id', '=', 'validaciones.id_documento')
            ->select('asistentes.*', 'documentos.foto_asistente', 'documentos.constancia_escuela', 'validaciones.id as id', 'validaciones.id_documento', 'validaciones.estado as estado_validacion')
            ->get()
            ->last();

        if (!$validacion) {
            return redirect()->route('revisores.lista-espera')->with('jsAlert', 'No se encontraron documentos pendientes de validación para este concursante.');
        }

        return view('revisor.validacion', compact('validacion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Validaciones  $asistente (recibe el modelo Validaciones via route-model binding)
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validaciones $asistente)
    {
        // Solo Revisor y Administrador pueden validar
        if (Auth::guard('revisor')->user()->rol === 'Visualizador') {
            return redirect()->route('revisores.lista-espera')
                ->with('error', 'No tienes permisos para validar asistentes.');
        }

        $val = Validator::make($request->all(), [
            'fotoRechazo'            => ['nullable', 'string', 'max:50'],
            'textoFotoRechazo'       => ['exclude_if:fotoRechazo,null', 'nullable', 'string'],
            'constanciaRechazo'      => ['nullable', 'string', 'max:50'],
            'textoConstanciaRechazo' => ['exclude_if:constanciaRechazo,null', 'nullable', 'string'],
        ]);

        if ($val->fails()) {
            return redirect()->back()->withInput()->withErrors($val->errors());
        }

        $validacion = $asistente;
        $documento = Documentos::findOrFail($validacion->id_documento);
        $concursante = Asistente::findOrFail($documento->id_asistente);

        $fecha_actual = Carbon::now();
        $año_edicio_folio = $fecha_actual->year;
        $cursos_folio = [0, 0, 0];
        $cantidad_alumnos_aprobados = Validaciones::where('estado', 'Aprobado')->count() + 1;
        $alumnos_aprobados_folio = str_pad($cantidad_alumnos_aprobados, 3, '0', STR_PAD_LEFT);
        $fecha_formateada = $fecha_actual->setTimezone('GMT-7')->format('d_M_Y , g:ia');

        $concursosNombres = [];
        foreach ($concursante->concursos()->get() as $concursoItem) {
            $concursosNombres[] = $concursoItem->nombre;
        }
        $concursosTexto = implode(", ", $concursosNombres);

        // Guardar revisor que emitió el dictamen (Historia 14 - Auditoría)
        if (Auth::guard('revisor')->check()) {
            $validacion->id_revisor = Auth::guard('revisor')->id();
        }

        $correoEnviado = false;
        $mensajeFeedback = '';

        if (!$request->fotoRechazo && !$request->constanciaRechazo) {
            // ==========================================
            // CASO 1: APROBADO
            // ==========================================
            $validacion->estado = "Aprobado";
            $validacion->doc_aprobado = "Foto para credencial, Constancia escolar";
            $validacion->doc_rechazado = null;
            $validacion->comentario = "Documentación completa y aprobada.";
            $validacion->fecha_validacion = $fecha_formateada;

            $concursante->fecha_registro_aceptado = $fecha_formateada;

            foreach ($concursante->concursos()->get() as $curso_id) {
                if (isset($cursos_folio[$curso_id->id - 1])) {
                    $cursos_folio[$curso_id->id - 1] = 1;
                }
            }

            $folio = $año_edicio_folio . "-" . implode("", $cursos_folio) . "-" . $alumnos_aprobados_folio;
            $concursante->folio = $folio;
            $concursante->save();

            $validacion->save();

            // Enviar notificación automática por correo (Historia 13)
            try {
                Mail::to($concursante->correo_electronico, $concursante->nombre)
                    ->send(new NotificacionValidacionAprobada($concursante, $validacion, $concursosTexto));
                $correoEnviado = true;
            } catch (\Throwable $e) {
                Log::error("Error al enviar correo de aprobación a {$concursante->correo_electronico}: " . $e->getMessage());
            }

            $mensajeFeedback = "¡Aspirante {$concursante->nombre} {$concursante->apellido_paterno} APROBADO con folio {$concursante->folio}!";
            if ($correoEnviado) {
                $mensajeFeedback .= " Se envió la notificación automática al correo: {$concursante->correo_electronico}.";
            } else {
                $mensajeFeedback .= " (Nota: El correo automático se registró en el sistema, revisa la configuración SMTP si no llegó a la bandeja).";
            }

        } else {
            // ==========================================
            // CASO 2: RECHAZADO CON OBSERVACIONES
            // ==========================================
            $doc_rechazo = [];
            if (!empty($request->fotoRechazo)) {
                $doc_rechazo[] = $request->fotoRechazo;
            }
            if (!empty($request->constanciaRechazo)) {
                $doc_rechazo[] = $request->constanciaRechazo;
            }

            $comentario = [];
            if (!empty($request->fotoRechazo)) {
                if (empty(trim($request->textoFotoRechazo))) {
                    return redirect()->back()->withInput()->with('jsAlert', "Faltó escribir la razón de rechazo para la Foto de credencial.");
                }
                $comentario[] = "Foto de credencial: " . trim($request->textoFotoRechazo);
            }
            if (!empty($request->constanciaRechazo)) {
                if (empty(trim($request->textoConstanciaRechazo))) {
                    return redirect()->back()->withInput()->with('jsAlert', "Faltó escribir la razón de rechazo para la Constancia escolar.");
                }
                $comentario[] = "Constancia escolar: " . trim($request->textoConstanciaRechazo);
            }

            $validacion->doc_rechazado = implode(", ", $doc_rechazo);
            $validacion->comentario = implode("\n", $comentario);
            $validacion->estado = "Rechazado";
            $validacion->fecha_validacion = $fecha_formateada;
            $validacion->save();

            // Enviar notificación automática por correo (Historia 13)
            try {
                Mail::to($concursante->correo_electronico, $concursante->nombre)
                    ->send(new NotificacionValidacionRechazada($concursante, $validacion, $concursosTexto));
                $correoEnviado = true;
            } catch (\Throwable $e) {
                Log::error("Error al enviar correo de rechazo a {$concursante->correo_electronico}: " . $e->getMessage());
            }

            $mensajeFeedback = "El aspirante {$concursante->nombre} {$concursante->apellido_paterno} fue puesto en estatus de RECHAZADO / CON OBSERVACIONES.";
            if ($correoEnviado) {
                $mensajeFeedback .= " Se envió la notificación automática con las correcciones requeridas a: {$concursante->correo_electronico}.";
            } else {
                $mensajeFeedback .= " (Nota: El correo automático se registró en el sistema, revisa la configuración SMTP si no llegó a la bandeja).";
            }
        }

        return redirect()->route('revisores.lista-espera')->with('notification', $mensajeFeedback);
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
