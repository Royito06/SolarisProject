**FASE 1: Arquitectura de Datos y Autenticación (El Cimiento)**:

1. **Versión del Framework (Importante):** El proyecto está construido sobre **Laravel 7** (`^7.29`) y está preparado para correr en PHP 7.2 u 8.0. Esto es un dato vital porque Laravel 7 es una versión antigua; si decidimos instalar paquetes nuevos, tendremos que buscar versiones antiguas que sean compatibles. Además, en Laravel 7, los modelos generalmente se guardaban directamente en la carpeta `app/` y no en `app/Models/`.


2. **Frontend:** El desarrollador original usó el paquete `laravel/ui` para estructurar la autenticación y las vistas. El diseño visual está basado en **Bootstrap 4** y **jQuery**, aunque también hay rastros de configuración para **Vue.js** (`vue-template-compiler`). Tendremos esto en cuenta para diseñar tu "Panel de administración de concursantes".


3. **Roles y Permisos:** Al revisar las dependencias, **no existe ningún gestor de roles instalado** (como el popular *Spatie Permission*). Esto significa que, para tus Historias de Usuario 1, 3, 6 y 7, tendremos que decidir si instalamos un paquete compatible con Laravel 7 o si programamos un sistema de roles sencillo (ej. agregar una columna `role` a la tabla de usuarios).


4. **Base de datos y Documentación:** El entorno está preparado para una base de datos **MySQL** tradicional. El desarrollador no dejó notas en el archivo `README.md`, ya que contiene el texto por defecto que trae Laravel al instalarse.

__________________________________________________________________

**FASE 2: Mapeo de la Base de Datos**

Al analizar las imágenes, me he dado cuenta de que **el desarrollador original ya había contemplado (o al menos iniciado) la figura del "Revisor" y la lógica de "Validaciones"**.

Fíjate en esto:

1. En la carpeta `app/` ya existe un modelo llamado `Revisores.php` y otro llamado `Validaciones.php`.


2. En las migraciones existe el archivo `2021_02_17_044608_create_revisores_table.php` y `2021_02_17_044555_create_validaciones_table.php`.


3. Además, basándome en los nombres, parece que a los "concursantes" (como los llamas en tus historias de usuario) el sistema internamente los llama **"Asistentes"** (`Asistente.php` y `...create_asistentes_table.php`).


Esto es una excelente noticia porque significa que no empezaremos desde cero. Sin embargo, para que el equipo pueda cumplir las Historias de Usuario relacionadas con permisos, carga de correos y validación (Historias 1, 3, 6, 7 y 11), necesitamos ver exactamente **cómo** construyó este desarrollador esas tablas y si las conectó correctamente.
atos):**
4. `database/migrations/2014_10_12_000000_create_users_table.php`
5. `database/migrations/2021_02_17_044608_create_revisores_table.php`
6. `database/migrations/2021_02_17_044425_create_asistentes_table.php`

__________________________________________________________________

El análisis del código revela una situación clásica en la gestión de proyectos heredados: el desarrollador anterior dejó la arquitectura a medias y con inconsistencias críticas.

Aquí está el diagnóstico exacto de la base de datos y lo que tu equipo debe solucionar antes de avanzar:

* **La ilusión del "Revisor":** El modelo `Revisores.php` existe y define un *guard* personalizado, pero si revisas la migración `...create_revisores_table.php`, **todo el método `up()` está comentado**. Esto significa que la tabla `revisores` ni siquiera existe en la base de datos actual. La buena noticia es que el código comentado ya proponía un sistema de roles (`Revisor`, `Visualizador`, `Administrador`) que encaja perfectamente con tus Historias de Usuario 3 y 7. Solo tendrán que descomentarlo y ajustarlo.
* **Los verdaderos concursantes son los "Asistentes":** El modelo `Asistente.php` y su migración están perfectamente sincronizados y estructurados. Cuentan con campos robustos (`curp`, `folio`, `escuela_origen`) y manejan las relaciones clave con `Concursos` y `Documentos`. Toda tu lógica de "validación de concursantes" (Historia 11) pivotará sobre este modelo.
* **Inconsistencia en el modelo `User`:** Existe una desconexión grave. La migración `...create_users_table.php` fue modificada para usar columnas en español (`nombre`, `correo_electronico`, `contrasena`), pero el modelo `app/User.php` sigue esperando los atributos nativos de Laravel en inglés (`'name'`, `'email'`, `'password'`). Si el sistema intenta crear un registro usando `User::create()`, arrojará un error de SQL.

### El plan de acción para tu equipo (Fase de Refactorización)

Para cumplir con la "Carga de correos de revisores" (Historia 1) y la "Asignación automática de permisos" (Historia 6), no necesitamos instalar paquetes complejos. El equipo deberá:

1. Descomentar y ejecutar la migración de `revisores`.
2. Ignorar el modelo `User` por ahora, ya que la aplicación parece usar `Asistente` para los estudiantes y usará `Revisores` para la administración.
3. 
__________________________________________________________________

**FASE 3: Enrutamiento y Flujo Actual**:

El desarrollador original dejó la infraestructura de autenticación perfectamente configurada, pero cometió un error crítico de seguridad en las rutas.

Aquí tienes el diagnóstico exacto de esta Fase 3 y lo que tu equipo debe ajustar:

1. **Multi-autenticación funcional:** El archivo `config/auth.php` está impecable. El sistema reconoce dos "guards" (tipos de sesión) distintos: `web` para los concursantes (Asistentes) y `revisor` para los validadores. Esto significa que si un concursante intenta acceder con su cuenta a la zona de validación, el sistema lo rechazará si aplicamos el filtro correcto.
2. **Punto de acceso de revisores detectado:** Las rutas revelan que los revisores no inician sesión en la página principal, sino en la ruta `/admin`, la cual es gestionada por el controlador `LoginRevisorController`.
3. **Falla de seguridad grave (Urgente):** El grupo de rutas `Route::prefix('revisores')` tiene definidos los paneles de administración (`lista-espera`, `lista-inscritos`) y las vistas de validación (`/{asistente}/validacion`), lo cual cubre las **Historias 4, 8, 10 y 11**. Sin embargo, **no están protegidas por ningún middleware**. Cualquier persona que escriba `[tusitio.com/revisores](https://tusitio.com/revisores)` en el navegador podrá ver los datos sin iniciar sesión.

Para solucionar esto, tu equipo tendrá que modificar el archivo `routes/web.php` y envolver ese grupo con el middleware de autenticación del revisor, de esta forma:

```php
Route::prefix('revisores')->middleware('auth:revisor')->group(function () {
    // Las rutas actuales...
});

```
__________________________________________________________________

**FASE 4: Servicios Adicionales (Automatización)**

El análisis de estos controladores nos da luz verde para empezar a programar. El desarrollador original protegió el controlador con el middleware `auth:revisor`, y dejó los métodos de recursos (`create`, `store`, etc.) completamente vacíos. Esto es ideal para nosotros.

Para cumplir con la **Historia 1 (Carga de correos)** y la **Historia 6 (Asignación automática de permisos)**, utilizaremos esos métodos vacíos. El "Desarrollador Principal" (Administrador) usará un formulario donde pegará una lista de correos institucionales, y el sistema los registrará automáticamente asignándoles el rol de "Revisor".

Aquí tienes las instrucciones y el código exacto que tu equipo debe implementar:

### Paso 1: Actualizar el Modelo (Permisos)

Para que el sistema sepa qué privilegios tiene cada correo, necesitamos habilitar la columna `rol`.

* Abran el archivo `app/Revisores.php`.
* Agreguen `'rol'` y los campos de nombre al arreglo `$fillable`. Debería quedar así:

```php
    protected $fillable = [
        'nombre', 'apellido_paterno', 'apellido_materno', 'correo', 'contrasena', 'rol'
    ];

```

### Paso 2: Programar la Lógica de Carga (Historia 1 y 6)

Vamos a darle vida al método vacío `store` dentro de `app/Http/Controllers/RevisoresController.php`. Este código tomará una cadena de correos separados por comas, los filtrará, y creará las cuentas con una contraseña temporal y su permiso correspondiente.

* Reemplacen el método `store` vacío por el siguiente código:

```php
    public function store(Request $request)
    {
        // 1. Validamos que el admin envíe texto y seleccione un rol
        $request->validate([
            'correos' => 'required|string', 
            'rol'     => 'required|in:Revisor,Visualizador,Administrador'
        ]);

        // 2. Separamos los correos por comas
        $correosArray = explode(',', $request->correos);
        $registrosExitosos = 0;

        foreach($correosArray as $correo) {
            $correoLimpio = trim($correo);
            
            // 3. Verificamos que tenga formato de correo válido
            if(filter_var($correoLimpio, FILTER_VALIDATE_EMAIL)) {
                
                // 4. Historia 6: Asignación automática. Creamos al revisor si no existe.
                Revisores::firstOrCreate(
                    ['correo' => $correoLimpio], // Condición de búsqueda
                    [
                        'nombre'     => 'Revisor',
                        'apellido_paterno' => 'Designado',
                        'contrasena' => bcrypt('Concurso2026!'), // Contraseña temporal
                        'rol'        => $request->rol
                    ]
                );
                $registrosExitosos++;
            }
        }

        // Retornamos a la vista con un mensaje de éxito
        return redirect()->back()->with('success', "Se han cargado y otorgado permisos a {$registrosExitosos} revisores exitosamente.");
    }

```

*Nota de equipo:* Para no complicar la fase de registro, generamos una contraseña genérica encriptada (`Concurso2026!`). Los revisores usarán esa contraseña la primera vez en el Login y después podrán cambiarla.

### Paso 3: Habilitar la Ruta de Carga

Abran el archivo `routes/web.php` y agreguen la ruta POST que conectará su vista (el formulario en HTML) con la función que acabamos de crear. Debe ir dentro del grupo que ya está protegido:

```php
Route::prefix('revisores')->middleware('auth:revisor')->group(function () {
    // ... rutas anteriores ...
    
    // Nueva ruta para procesar la carga de correos
    Route::post('/cargar-correos', 'RevisoresController@store')->name('revisores.store');
});

```

### Siguiente paso para el equipo

Con esto, el "motor" para dar de alta revisores está listo. Ahora, el equipo de Front-end necesita diseñar un formulario sencillo (Historia 5) que envíe por método `POST` un campo de texto llamado `correos` (donde irán los correos separados por comas) y un `<select>` llamado `rol` a la ruta `route('revisores.store')`.

¿Quieres que analice cómo están construidas las vistas actuales (`revisor.index`, `revisor.listaEspera`) para darles la estructura HTML/Blade exacta de este formulario y del panel, o prefieren que nos enfoquemos en la lógica de la **Historia 11 (Validación de concursantes)**?
