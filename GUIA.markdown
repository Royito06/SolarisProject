### Fase 1: Cimientos de Seguridad y Base de Datos

Antes de programar nuevas interfaces, el equipo debe habilitar la infraestructura que el desarrollador original dejó a medias y asegurar el sistema.

* **Acciones técnicas:**
* Ir a `database/migrations/` y descomentar todo el contenido de `2021_02_17_044608_create_revisores_table.php` para que la tabla exista en MySQL.
* Ir a `routes/web.php` y envolver el `Route::prefix('revisores')` con el `middleware('auth:revisor')` para tapar la brecha de seguridad.
* Ignorar el modelo `User` por completo.


* **Historias de Usuario completadas aquí:**
* **Historia 7 (Gestión de permisos de revisor):** Al activar la tabla con la columna `rol` (`Revisor`, `Visualizador`, `Administrador`) y aplicar el middleware, se sientan las bases de los permisos.



### Fase 2: Módulo de Carga y Asignación de Revisores

Con la tabla creada, el siguiente paso es construir el mecanismo para que el Administrador registre a su equipo de revisión de forma masiva.

* **Acciones técnicas:**
* Añadir `'rol'` al arreglo `$fillable` en `app/Revisores.php`.
* Implementar la lógica en `RevisoresController@store` que recibe una cadena de texto, la separa por comas (`explode`), valida que sean correos, y usa `Revisores::firstOrCreate` para guardar las cuentas con una contraseña temporal.
* Crear una vista sencilla con un formulario y un `<select>` para elegir el nivel de permiso a otorgar.


* **Historias de Usuario completadas aquí:**
* **Historia 1 (Carga de los correos de los revisores):** Cumplida mediante el formulario que recibe el listado separado por comas.
* **Historia 3 (Otorgar autorización a correos):** Cumplida al vincular un rol específico desde el formulario a los correos cargados.
* **Historia 6 (Asignación automática de permisos):** Cumplida por la lógica del controlador que inyecta los privilegios en la base de datos sin intervención manual por cada usuario.



### Fase 3: Diseño e Integración del Panel de Control (Frontend)

Una vez que los revisores pueden iniciar sesión (en la ruta `/admin`), necesitan aterrizar en un entorno visual funcional.

* **Acciones técnicas:**
* Aprovechar que el proyecto utiliza Bootstrap 4 para diseñar un archivo `layout.blade.php` exclusivo para los revisores, separándolo del diseño que ven los concursantes.
* Construir la vista principal (`revisor.index` o `revisor.listaInscritos`) utilizando tablas HTML responsivas para mostrar los datos de manera limpia.


* **Historias de Usuario completadas aquí:**
* **Historia 4 (Panel de administración de concursantes):** La creación del entorno general donde trabajarán los revisores.
* **Historia 5 (Diseño de layout del panel de revisor):** La maquetación HTML/CSS (Bootstrap) de la barra de navegación, menús laterales y estructura base.
* **Historia 9 (Salida y actualización del panel de datos):** Asegurar que las vistas recarguen o reflejen los datos actuales tras cualquier acción en la tabla.



### Fase 4: Motor de Ingesta de Datos y Búsqueda

El panel ya existe visualmente; ahora hay que alimentarlo con la información real de los aspirantes al concurso de Matemáticas y Física.

* **Acciones técnicas:**
* Modificar las consultas en `RevisoresController` (ej. `listaInscritos()`) para extraer los registros del modelo `Asistente` (concursantes), uniéndolos (`JOIN` o *Eloquent Relationships*) con la tabla de `Documentos`.
* Implementar una barra de búsqueda en la vista y lógica en el controlador (usando consultas condicionales `where` o *scopes*) para filtrar por nombre, folio o escuela de origen.
* Crear una vista modal o una página individual (`/{asistente}/validacion`) para mostrar el detalle completo y cargar los archivos adjuntos del concursante.


* **Historias de Usuario completadas aquí:**
* **Historia 2 (Obtener información de los concursantes):** Extraer los datos del modelo `Asistente` y mandarlos a las vistas.
* **Historia 8 (Vista de detalles y documentos del concursante):** Construir la interfaz de desglose individual para revisión profunda.
* **Historia 10 (Filtro y búsqueda de inscritos):** Implementación del buscador en el panel principal.



### Fase 5: Flujo de Validación y Trazabilidad (El Core)

Esta es la fase de interacción principal donde el revisor decide si el concursante cumple los requisitos.

* **Acciones técnicas:**
* Ajustar `ValidacionesController@update`. Al recibir un `POST/PUT` aprobando o rechazando a un participante, actualizar la tabla `validaciones`.
* **Punto crítico:** Al momento de hacer el `update` o `insert` en la validación, el sistema debe capturar el ID del revisor en sesión usando `Auth::guard('revisor')->user()->id` y guardarlo en la base de datos junto con la marca de tiempo.


* **Historias de Usuario completadas aquí:**
* **Historia 11 (Cambio de estado / Validación de concursantes):** La acción de aprobar, rechazar o mandar a lista de espera.
* **Historia 14 (Registro del autor de la validación - Auditoría):** El guardado silencioso de qué revisor específico emitió el dictamen.



### Fase 6: Salida de Datos y Notificaciones (Automatización Final)

El proceso concluye informando a los participantes y generando los reportes para la organización del torneo.

* **Acciones técnicas:**
* Programar un exportador (usando librerías de Laravel o el paquete `Maatwebsite/Laravel-Excel` si deciden instalarlo) que tome la lista de `Asistentes` validados y devuelva un archivo descargable.
* Configurar un `Mailable` de Laravel que se dispare automáticamente mediante *Events* u *Observers* justo después de que la Historia 11 (cambio de estado) se ejecute, enviando un correo al concursante usando las credenciales SMTP de `config/mail.php`.


* **Historias de Usuario completadas aquí:**
* **Historia 12 (Exportación de datos):** La descarga de la tabla procesada en formato útil (CSV o Excel).
* **Historia 13 (Notificación automática de validación):** El cierre del ciclo comunicando la resolución final al estudiante inscrito.

_______________________________________________

Al analizar detenidamente el plan de ruta frente a los fragmentos de código heredado que compartiste, encontré tres detalles técnicos críticos u omisiones que le causarán errores de SQL a ti, a Rodrigo y a Alberto si no los ajustan antes de comenzar a programar.

Las 14 historias de usuario están correctamente asignadas en sus respectivas fases, pero deben agregar estas tres correcciones a la guía técnica del equipo:

**1. Error de cardinalidad en el modelo heredado (Afecta la Fase 5 - Historia 14)**
En el código de `app/Revisores.php`, el desarrollador original dejó la relación escrita de esta forma:
`public function validaciones(){ return $this->belongsTo(Validaciones::class); }`
Esto es un error de lógica de base de datos. Un revisor no "pertenece" a una sola validación; un revisor emite **muchas** validaciones.

* **Corrección en el plan:** Quien tome esta tarea debe cambiar ese método para usar la relación correcta: `return $this->hasMany(Validaciones::class, 'id_revisor');`. De lo contrario, el rastreo de auditoría fallará al intentar recuperar el historial.

**2. Campos obligatorios en la migración de Revisores (Afecta la Fase 2 - Historias 1 y 6)**
El plan indica que el sistema recibirá una lista de correos separados por comas y los guardará automáticamente. Sin embargo, la migración comentada `...create_revisores_table.php` exige `nombre`, `apellido_paterno` y `apellido_materno`. Al intentar guardar solo el correo, MySQL arrojará un error fatal por campos faltantes.

* **Corrección en el plan:** Antes de ejecutar la migración, el equipo debe modificar el archivo para permitir valores nulos en esos campos (ej. `$table->string('nombre')->nullable();`), o asegurarse de inyectar cadenas de texto genéricas (como `'Por definir'`) en el método `firstOrCreate` del controlador.

**3. Verificación de la columna de Auditoría (Afecta la Fase 5 - Historia 14)**
El plan establece que se debe capturar el ID del revisor en sesión (`Auth::guard('revisor')->user()->id`) y guardarlo en la tabla `validaciones`. Dado que no analizamos el interior del archivo `...create_validaciones_table.php`, existe el riesgo de que el desarrollador original no haya contemplado esta columna.

* **Corrección en el plan:** El equipo debe abrir la migración de `validaciones` y confirmar que exista un campo destinado a esto (idealmente `$table->unsignedBigInteger('id_revisor');` con su respectiva llave foránea). Si no existe, deberán crear una nueva migración (`php artisan make:migration add_id_revisor_to_validaciones_table`) para añadir la columna, de lo contrario los datos de la Historia 14 no tendrán dónde almacenarse.

**ERRORES E IRREGULARIDADES**

