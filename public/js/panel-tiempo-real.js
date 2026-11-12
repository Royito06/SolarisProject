/**
 * Actualización en tiempo real del panel de asistentes (concursantes).
 *
 * Cada pocos segundos consulta /revisores/panel-data/{vista}?v=<version>:
 *   - Si nada cambió, el servidor responde {changed:false} (respuesta mínima) y no se toca la tabla.
 *   - Si cambió algo (nuevo registro, documentos enviados, estatus validado/rechazado, etc.)
 *     devuelve las filas ya renderizadas y aquí solo se reemplaza el <tbody>.
 *
 * Detalles pensados para uso real:
 *   - Una petición a la vez (setTimeout encadenado, no setInterval) y con tiempo límite.
 *   - Se pausa cuando la pestaña está oculta y refresca de inmediato al volver a verla.
 *   - Si se cae la red reintenta con espera creciente (5s, 10s, 20s ... máx. 60s).
 *   - Si la sesión expira (401/419) se detiene y avisa en lugar de reintentar sin fin.
 *   - Conserva el texto del buscador y resalta unos segundos las filas que cambiaron.
 */
(function () {
  'use strict';

  var contenedor = document.getElementById('panel-live');
  if (!contenedor) return; // esta página no tiene panel en vivo

  var url = contenedor.dataset.url;
  var tbody = document.getElementById(contenedor.dataset.tbody || 'listaTabla');
  if (!url || !tbody) return;

  var INTERVALO = Math.max(2000, parseInt(contenedor.dataset.intervalo, 10) || 5000);
  var INTERVALO_MAX = 60000;
  var TIEMPO_LIMITE = 10000;

  var insignia = document.getElementById('panel-live-estado');
  var etiquetaHora = document.getElementById('panel-live-hora');
  var botonRefrescar = document.getElementById('panel-live-refrescar');

  var version = contenedor.dataset.version || '';
  var fallos = 0;
  var temporizador = null;
  var enCurso = false;
  var detenido = false;

  function mostrar(clase, texto) {
    insignia.className = 'badge ' + clase;
    insignia.textContent = texto;
  }

  function programar(ms) {
    clearTimeout(temporizador);
    if (!detenido) temporizador = setTimeout(consultar, ms);
  }

  function intervaloActual() {
    return Math.min(INTERVALO * Math.pow(2, fallos), INTERVALO_MAX);
  }

  function detener() {
    detenido = true;
    clearTimeout(temporizador);
    mostrar('badge-danger', 'Sesión expirada');
    etiquetaHora.innerHTML = '<a href="/admin">Inicia sesión de nuevo</a> para reanudar la actualización.';
  }

  function aplicar(datos) {
    // Huella de cada fila antes del cambio, para saber cuáles cambiaron o son nuevas
    var previas = {};
    tbody.querySelectorAll('tr[data-id]').forEach(function (tr) {
      previas[tr.dataset.id] = tr.dataset.hash;
    });

    tbody.innerHTML = datos.html;
    version = datos.version;

    tbody.querySelectorAll('tr[data-id]').forEach(function (tr) {
      if (previas[tr.dataset.id] !== tr.dataset.hash) {
        tr.classList.add('fila-actualizada');
      }
    });

    // Vuelve a aplicar el texto que el revisor tenga escrito en el buscador
    if (typeof buscadorPorNombre === 'function') buscadorPorNombre();
  }

  function consultar() {
    if (detenido || enCurso) return;

    if (document.hidden) {
      mostrar('badge-secondary', 'En pausa (pestaña oculta)');
      return; // visibilitychange la reanuda
    }

    enCurso = true;
    var controlador = new AbortController();
    var corte = setTimeout(function () { controlador.abort(); }, TIEMPO_LIMITE);

    fetch(url + '?v=' + encodeURIComponent(version), {
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
      cache: 'no-store',
      signal: controlador.signal
    })
      .then(function (respuesta) {
        if (respuesta.status === 401 || respuesta.status === 419) {
          detener();
          return null;
        }
        if (!respuesta.ok) throw new Error('HTTP ' + respuesta.status);
        return respuesta.json();
      })
      .then(function (datos) {
        if (!datos) return;
        if (datos.changed) aplicar(datos);
        fallos = 0;
        mostrar('badge-success', 'En vivo');
        etiquetaHora.textContent = 'Actualizado a las ' + new Date().toLocaleTimeString('es-MX');
      })
      .catch(function () {
        fallos++;
        mostrar('badge-warning', 'Sin conexión, reintentando…');
      })
      .then(function () {
        clearTimeout(corte);
        enCurso = false;
        programar(intervaloActual());
      });
  }

  function consultarYa() {
    clearTimeout(temporizador);
    consultar();
  }

  botonRefrescar.addEventListener('click', consultarYa);

  // Al volver a la pestaña, o al recuperar internet, se actualiza de inmediato
  document.addEventListener('visibilitychange', function () {
    if (!document.hidden) consultarYa();
  });
  window.addEventListener('online', consultarYa);
  // Botón "atrás" del navegador: la página puede venir de caché con datos viejos
  window.addEventListener('pageshow', function (e) {
    if (e.persisted) consultarYa();
  });

  mostrar('badge-success', 'En vivo');
  etiquetaHora.textContent = 'Actualizado a las ' + new Date().toLocaleTimeString('es-MX');
  programar(INTERVALO);
})();
