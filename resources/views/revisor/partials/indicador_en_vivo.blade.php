{{--
    Indicador de actualización automática del panel.
    Lo lee public/js/panel-tiempo-real.js (atributos data-*).
    Parámetros: $vista (espera|asistentes|inscritos) y $version (huella de los datos ya mostrados).
--}}
<div id="panel-live" class="d-flex align-items-center mb-2"
     data-url="{{ route('revisores.panel-data', $vista) }}"
     data-version="{{ $version }}"
     data-intervalo="5000"
     data-tbody="listaTabla">
    <span id="panel-live-estado" class="badge badge-secondary">Conectando…</span>
    <small id="panel-live-hora" class="text-muted ml-2"></small>
    <button type="button" id="panel-live-refrescar" class="btn btn-xs btn-outline-secondary ml-auto">Actualizar ahora</button>
</div>
