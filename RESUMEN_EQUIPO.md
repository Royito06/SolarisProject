# 📋 Guía Paso a Paso para Probar y Verificar el Sistema

Esta guía está diseñada para que cualquier integrante del equipo pueda configurar, ejecutar y comprobar en menos de 5 minutos que todo el sistema funciona al 100%.

---

## 🛠️ PASO 1: Descargar el Proyecto
1. Clona el repositorio desde GitHub:
   ```bash
   git clone https://github.com/Royito06/SolarisProject.git
   ```
   *(O descarga y descomprime la carpeta del proyecto en tu computadora).*
2. Entra a la carpeta `SolarisProject`.

---

## ⚙️ PASO 2: Instalación Automática (Solo se hace 1 vez)
> [!NOTE]
> No necesitas tener instalado PHP, MySQL ni Composer. El proyecto incluye un entorno portátil preconfigurado.

1. Haz doble clic sobre el archivo **`instalar.bat`**.
2. Se abrirá una ventana azul/negra que automáticamente:
   - Descargará y configurará **PHP 8.0**, **MariaDB 10.6** y **Node.js** en `C:\tools`.
   - Creará el archivo de configuración `.env` con las credenciales correctas.
   - Instalará todas las dependencias de Laravel (`vendor/`).
   - Iniciará la base de datos `concursofm` en el puerto `3307`.
   - Ejecutará las migraciones y sembradores (`migrate --seed`).
3. **¿Cómo saber que terminó bien?:**  
   Verás un mensaje verde en letras grandes:  
   `¡INSTALACION Y CONFIGURACION COMPLETADAS CON EXITO!`  
   Presiona Enter para cerrar esa ventana.

---

## 🚀 PASO 3: Arrancar el Sistema
1. Haz doble clic sobre el archivo **`iniciar.bat`**.
2. Se abrirá una ventana de comandos de Windows y automáticamente se abrirá tu navegador en:  
   👉 **http://127.0.0.1:8000**
3. > [!IMPORTANT]
   > **Regla de oro:** No cierres la ventana negra de comandos de `iniciar.bat` mientras estés utilizando la plataforma; esa ventana es el servidor web activo.

---

## 🔐 PASO 4: Iniciar Sesión como Administrador
1. En tu navegador entra a:  
   👉 **[http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)**
2. Ingresa con las credenciales de administrador:
   - **Correo:** `admin@unison.mx`
   - **Contraseña:** `Admin2026!`
3. Haz clic en **Acceder**.  
   *Resultado esperado:* Entrarás directamente a la pantalla **Gestión y Carga de Revisores**.

---

## 🧪 PASO 5: Comprobar la Carga de Revisores (Fase 2)

### Prueba A: Registro Manual de Correos
1. En el panel, ubica la sección de la derecha: **Opción B: Ingresar correo(s) manualmente**.
2. En la caja de texto escribe dos correos separados por comas, por ejemplo:
   ```text
   profesor.mate@unison.mx, profesor.fisica@unison.mx
   ```
3. En el selector de abajo, elige el rol: **Revisor**.
4. Haz clic en el botón azul **Guardar y Registrar Revisores**.
5. *Resultado esperado:*
   - Aparecerá una alerta verde: `¡Operación completada! Se registraron 2 nuevo(s) revisor(es)...`
   - En la tabla de abajo aparecerán ambos correos con su badge verde `Revisor`.

---

### Prueba B: Registro Masivo con Archivo (.txt o .csv)
1. En tu computadora, abre el **Bloc de Notas** de Windows.
2. Escribe una lista de correos (pueden estar separados por comas o uno por renglón):
   ```text
   evaluador1@unison.mx, evaluador2@unison.mx
   evaluador3@unison.mx
   ```
3. Guarda el archivo en tu escritorio como `correos.txt`.
4. En el panel web, ubica la sección de la izquierda: **Opción A: Cargar archivo (.txt / .csv)**.
5. Haz clic en **Elegir archivo** y selecciona tu archivo `correos.txt`.
6. En el selector de rol, elige: **Visualizador**.
7. Haz clic en **Guardar y Registrar Revisores**.
8. *Resultado esperado:*
   - Alerta verde confirmando el alta de los 3 usuarios.
   - En la tabla inferior aparecerán los 3 nuevos revisores con el badge azul `Visualizador`.

---

## ✉️ PASO 6: Validación de Concursantes y Notificaciones Automáticas (Historias 11, 13 y 14)

### ¿Cómo funciona el flujo de validación automática?
1. En el menú lateral izquierdo del panel de administración, entra a **Asistentes > Asistentes en espera** (`/revisores/lista-espera`).
2. Haz clic en el botón **Validar** del concursante a evaluar.
3. Se abrirá la vista de revisión de documentos (`/revisores/{id}/validacion`):
   - **Caso Aprobado:** Si ambos documentos se dejan en **Aceptar** y se da clic en **Validar**:
     - Se genera el folio oficial del alumno (ej: `2026-100-001`).
     - Se registra qué revisor emitió el dictamen (`id_revisor`) y fecha/hora (Historia 14).
     - **Notificación Automática por Correo:** Se dispara `NotificacionValidacionAprobada` al correo del concursante con su folio oficial, categorías y detalles de bienvenida (Historia 13).
     - **Notificación en el Portal:** Al ingresar el concursante a su perfil (`/asistentes`), verá un banner verde destacado informándole que su inscripción fue aprobada junto con su folio.
   - **Caso Rechazado con Observaciones:** Si se selecciona **Rechazar** en alguno de los documentos (ej. Foto o Constancia) y se escribe la observación requerida:
     - El estatus cambia a `Rechazado`.
     - **Notificación Automática por Correo:** Se dispara `NotificacionValidacionRechazada` indicando específicamente qué documento fue rechazado y las observaciones para corregirlo.
     - **Notificación en el Portal:** El concursante ve una alerta roja con el detalle de las observaciones y un botón directo para **Corregir y Reenviar Documentos**.

> [!TIP]
> Por defecto en entorno local, el driver de correo está en `MAIL_MAILER=log`. Cada correo generado se registra íntegro con su HTML y cabeceras en `storage/logs/laravel.log`. Si se desea enviar por un servidor SMTP real (Gmail, Mailtrap, o servidor de la Unison), simplemente se ajusta en el archivo `.env`.

---

## 🔑 PASO 7: Comprobar el Acceso de un Revisor Recién Creado
1. En la esquina superior derecha del panel administrativo, haz clic en el botón **Salir**.
2. El sistema te regresará a `http://127.0.0.1:8000/admin`.
3. Inicia sesión con cualquiera de los correos que diste de alta:
   - **Correo:** `profesor.mate@unison.mx`
   - **Contraseña temporal asignada:** `Concurso2026!`
4. Haz clic en **Acceder**.  
   *Resultado esperado:* El revisor podrá entrar al sistema con su propia cuenta y ver su nivel de permisos asignado.

---

## 🛑 PASO 8: Detener el Servidor al Terminar
Cuando termines de trabajar:
1. Ve a la ventana negra de comandos de `iniciar.bat`.
2. Presiona <kbd>Ctrl</kbd> + <kbd>C</kbd> o simplemente cierra la ventana.
