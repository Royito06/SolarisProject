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
        $asistentes = Asistente::with(['documentos.validaciones'])->get();

        return view('revisor.listaEspera', compact('asistentes'));
    }

    public function listaAsistentes()
    {
        $asistentes = Asistente::with(['documentos.validaciones', 'concursos', 'asesor'])->get();

        return view('revisor.listaAsistentes', compact('asistentes'));
    }

    public function listaInscritos()
    {
        $asistentesInscritos = Asistente::with(['documentos.validaciones', 'concursos'])->get();

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
            'rol.required'          => 'Debes seleccionar un rol para los usuarios.',
            'rol.in'                => 'El rol seleccionado no es válido.',
        ]);

        $rawText = '';

        // Si se subió un archivo .txt o .csv, leemos su contenido
        if ($request->hasFile('archivo_correos')) {
            $rawText .= file_get_contents($request->file('archivo_correos')->getRealPath()) . "\n";
        }

        // Si se escribieron usuarios manualmente, los concatenamos
        if (!empty(trim($request->correos_manual))) {
            $rawText .= " " . $request->correos_manual;
        }

        if (empty(trim($rawText))) {
            return redirect()->back()->with('error', 'Debes adjuntar un archivo (.txt o .csv) o ingresar al menos un usuario de forma manual.');
        }

        // Separamos solo por comas o espacios (incluyendo saltos de línea y tabulaciones)
        $tokens = preg_split('/[,\s]+/', trim($rawText), -1, PREG_SPLIT_NO_EMPTY);

        $items = [];
        $i = 0;
        $total = count($tokens);
        $invalidos = [];

        while ($i < $total) {
            $token = trim($tokens[$i]);

            // Verificamos si tiene estructura de usuario/correo electrónico
            if (filter_var($token, FILTER_VALIDATE_EMAIL)) {
                $usuario = strtolower($token);
                $password = null;

                // Si el siguiente token existe y no es otro usuario, corresponde a su contraseña
                if ($i + 1 < $total) {
                    $siguiente = trim($tokens[$i + 1]);
                    if (!filter_var($siguiente, FILTER_VALIDATE_EMAIL)) {
                        $password = $siguiente;
                        $i++; // Consumimos la contraseña correspondiente
                    }
                }

                // Si no se proporcionó contraseña posterior, se asigna la predeterminada
                if (empty($password)) {
                    $password = 'Concurso2026!';
                }

                $items[] = [
                    'usuario'  => $usuario,
                    'password' => $password,
                ];
            } else {
                $invalidos[] = $token;
            }
            $i++;
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'No se encontraron usuarios válidos en el archivo o texto ingresado.');
        }

        $nuevos = 0;
        $actualizados = 0;

        foreach ($items as $item) {
            $usuarioLimpio = $item['usuario'];
            $passwordLimpia = $item['password'];

            $partes = explode('@', $usuarioLimpio);
            $nombreBase = ucfirst(str_replace(['.', '_', '-'], ' ', $partes[0]));

            $revisor = Revisores::firstOrNew(['correo' => $usuarioLimpio]);

            if (!$revisor->exists) {
                $revisor->nombre = $nombreBase ?: 'Usuario';
                $revisor->apellido_paterno = 'Designado';
                $revisor->apellido_materno = 'Comité';
                $revisor->contrasena = bcrypt($passwordLimpia);
                $revisor->rol = $request->rol;
                $revisor->save();
                $nuevos++;
            } else {
                // Si ya existe, actualizamos su rol y su contraseña
                $revisor->rol = $request->rol;
                $revisor->contrasena = bcrypt($passwordLimpia);
                $revisor->save();
                $actualizados++;
            }
        }

        $mensaje = "¡Operación completada! Se registraron {$nuevos} nuevo(s) usuario(s)";
        if ($actualizados > 0) {
            $mensaje .= " y se actualizaron {$actualizados} existente(s) (incluyendo sus contraseñas y roles)";
        }
        $mensaje .= " con el rol '{$request->rol}'.";

        if (count($invalidos) > 0) {
            $mensaje .= " (Usuarios no válidos ignorados: " . implode(', ', array_slice($invalidos, 0, 5)) . (count($invalidos) > 5 ? '...' : '') . ")";
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
