<?php

namespace App\Http\Controllers;

use App\Asistente;
use App\Documentos;
use App\Validaciones;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Validaciones  $validaciones
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Validaciones $asistente)
    {
        //dd($request);
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
        //dd('paso');
/*         dd($asistente);
        $documento = Documentos::where('id_asistente', $asistente->id)->get()->last();
        dd($documento);
        $validacion = Validaciones::where('id_documento', $documento->id)->get()->last(); */
        $validacion = $asistente;
        //dd($validacion);
        $documento = Documentos::findOrFail($validacion->id_documento);
        //dd($documento);
        $asistente = Asistente::findOrFail($documento->id_asistente);
        //dd($asistente);
        $fecha_actual = Carbon::now();
        $año_edicio_folio = $fecha_actual->year;
        $cursos_folio = [0,0,0];
        $cantidad_alumnos_aprobados = count(Validaciones::where('estado','Aprobado')->get()) + 1;
        $alumnos_aprobados_folio = "".$cantidad_alumnos_aprobados;
        $fecha_actual = $fecha_actual->setTimezone('GMT-7')->format('d_M_Y , g:ia');

        $concursos = "";
        foreach ($asistente->concursos()->get() as $concursos_id) {
            $concursos .= $concursos_id->nombre . ", ";
        }

        if(!$request->fotoRechazo && /*!$request->actaRechazo &&*/ !$request->constanciaRechazo){
            //dd('aprobado');
            $validacion->estado = "Aprobado";

            $asistente->fecha_registro_aceptado = $fecha_actual;

            foreach ($asistente->concursos()->get() as $curso_id) {
                $cursos_folio[$curso_id->id-1] = 1;
            };
            $alumnos_aprobados_folio = str_pad($alumnos_aprobados_folio, 3, 0, STR_PAD_LEFT);

            $folio = $año_edicio_folio."-".implode("", $cursos_folio)."-".$alumnos_aprobados_folio;
            
            $asistente->folio = $folio;

            $asistente->save();

            //dd($concursos);
            $data = [
                "nombre" => $asistente->nombre, 
                "apellido_paterno" => $asistente->apellido_paterno, 
                "apellido_materno" => $asistente->apellido_materno, 
                "correo_electronico" => $asistente->correo_electronico, 
                "estado" => $validacion->estado,
                "concursos" => $concursos,
            ];
            Mail::send('vendor.notifications.email_aprobado', $data, function($message) use ($data) {
                $message->to($data['correo_electronico'], $data['nombre'])->bcc("adrianvo@mat.uson.mx")->subject('CRFyM - Confirmación de inscripción');
            });

        }else{
            
            $doc_rechazo = [$request->fotoRechazo, /*$request->actaRechazo,*/ $request->constanciaRechazo];
            $doc_rechazo = array_filter($doc_rechazo, function($v){return $v != null; });
            //dd($doc_rechazo);

            $comentario = [$request->textoFotoRechazo, /*$request->textoActaRechazo,*/ $request->textoConstanciaRechazo];
            $comentario = array_filter($comentario, function($v){return $v != null; });

            if(count($doc_rechazo) != count($comentario)){
                return redirect()->back()->withInput()->with('jsAlert', "Faltaron una o mas areas de texto por llenar");
            }
            $comentario = array_combine($doc_rechazo, $comentario);
            //dd($doc_rechazo, $comentario);
            
            foreach ($comentario as $key => $value) {
                $comentario[$key] = "$key: $value";
            }

            $doc_rechazo = implode(", ", $doc_rechazo);
            $comentario = implode("\n", $comentario);

            //dd($comentario, $doc_rechazo);

            $validacion->comentario = $comentario;
            $validacion->doc_rechazado = $doc_rechazo;
            $validacion->estado = "Rechazado";
            //dd('rechazo');

            //dd($validacion);
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
            Mail::send('vendor.notifications.email_rechazado', $data, function($message) use ($data) {
                $message->to($data['correo_electronico'], $data['nombre'])->bcc("adrianvo@mat.uson.mx")->subject('CRFyM - Inscripción pendiente');
            });
            //dd("dd");
        }

        $validacion->fecha_validacion = $fecha_actual;
        
        $validacion->save();

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
