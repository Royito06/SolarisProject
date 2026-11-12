/**
 * Utilidades del panel de revisores:
 *   1. Buscador de las listas.
 *   2. Modal "Info" con los datos del asistente.
 *
 * Los dos funcionan aunque la tabla se vuelva a pintar (refresco automático, ver
 * panel-tiempo-real.js): el buscador lee el atributo data-buscar de cada fila y el
 * modal usa delegación de eventos en lugar de enlazar cada botón al cargar la página.
 */

/* ------------------------------------------------------------------ */
/* 1. BUSCADOR                                                         */
/* ------------------------------------------------------------------ */

// minúsculas y sin acentos, para que "jose" encuentre "José"
function normalizarTexto(texto) {
  return (texto || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

// Se llama desde el onkeyup del input y después de cada refresco automático.
function buscadorPorNombre() {
  const input = document.getElementById('inputBuscadorNombre');
  if (!input) return;

  const filtro = normalizarTexto(input.value.trim());

  document.querySelectorAll('.fila-panel').forEach(function (tr) {
    const texto = normalizarTexto(tr.dataset.buscar);
    tr.style.display = (filtro === '' || texto.indexOf(filtro) > -1) ? '' : 'none';
  });
}

/* ------------------------------------------------------------------ */
/* 2. MODAL DE MÁS INFORMACIÓN                                         */
/* ------------------------------------------------------------------ */

let ultimaPeticionModal = 0; // evita que una respuesta lenta pise a una más reciente

document.addEventListener('click', function (e) {
  const boton = e.target.closest('.botonInfo');
  if (boton) {
    cargarInfoAsistente(boton.value);
  }
});

function cargarInfoAsistente(id) {
  const $ = function (x) { return document.getElementById(x); };

  const contenedores = ['divNombre', 'divNacimiento', 'divTelefono', 'divEscuela', 'divCorreo',
    'divCurp', 'divConcursos', 'divFolio', 'divEstatus', 'divImgAsistente', 'divImgAsistenteConstancia'];
  const etiquetas = ['fNacimiento', 'telefono', 'curp', 'identificacion', 'constancia', 'escuela', 'correo', 'concursos'];

  const mostrarEtiquetas = function (visible) {
    etiquetas.forEach(function (e) { $(e).style.display = visible ? '' : 'none'; });
  };
  const parrafo = function (texto) {
    const p = document.createElement('p');
    p.textContent = texto;
    return p;
  };
  const dato = function (valor) {
    return (valor === null || valor === undefined || valor === '') ? 'No registrado' : valor;
  };

  contenedores.forEach(function (c) { $(c).innerHTML = ''; });

  const token = ++ultimaPeticionModal;

  fetch('/wsgetuserdata/' + encodeURIComponent(id), {
    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    credentials: 'same-origin',
    cache: 'no-store'
  })
    .then(function (respuesta) {
      if (!respuesta.ok) throw new Error('HTTP ' + respuesta.status);
      return respuesta.json();
    })
    .then(function (data) {
      if (token !== ultimaPeticionModal) return; // llegó una petición más nueva

      const asistente = data[0];
      const nombreCompleto = [asistente.nombre, asistente.apellido_paterno, asistente.apellido_materno]
        .filter(Boolean).join(' ');

      $('divNombre').appendChild(parrafo(nombreCompleto));
      $('divFolio').appendChild(parrafo(asistente.folio ? asistente.folio : 'Alumno sin número de folio'));

      if (data[1]) {
        let estatus;
        if (data[1].estado == 'Espera') {
          estatus = 'En revisión';
        } else if (data[1].estado == 'Rechazado') {
          estatus = 'Renvío';
        } else {
          estatus = 'Inscrito';
        }

        const ulConcursos = document.createElement('ul');
        (data[2] || []).forEach(function (concurso) {
          const li = document.createElement('li');
          li.textContent = concurso.nombre;
          ulConcursos.appendChild(li);
        });

        mostrarEtiquetas(true);
        $('divNacimiento').appendChild(parrafo(dato(asistente.fecha_nacimiento)));
        $('divEscuela').appendChild(parrafo(dato(asistente.escuela_origen)));
        $('divCorreo').appendChild(parrafo(dato(asistente.correo_electronico)));
        $('divTelefono').appendChild(parrafo(dato(asistente.telefono)));
        $('divCurp').appendChild(parrafo(dato(asistente.curp)));
        $('divConcursos').appendChild(ulConcursos);
        $('divEstatus').appendChild(parrafo(estatus));

        if (asistente.documentos) {
          const imgFoto = document.createElement('img');
          imgFoto.src = '/storage/' + asistente.documentos.foto_asistente;
          $('divImgAsistente').appendChild(imgFoto);

          const imgConstancia = document.createElement('img');
          imgConstancia.src = '/storage/' + asistente.documentos.constancia_escuela;
          $('divImgAsistenteConstancia').appendChild(imgConstancia);
        }
      } else {
        // Todavía no ha enviado documentos
        mostrarEtiquetas(false);
        $('divEstatus').appendChild(parrafo('Enviando'));
      }
    })
    .catch(function () {
      if (token !== ultimaPeticionModal) return;
      mostrarEtiquetas(false);
      $('divNombre').appendChild(parrafo('No se pudo cargar la información. Cierra la ventana e inténtalo de nuevo.'));
    });
}
