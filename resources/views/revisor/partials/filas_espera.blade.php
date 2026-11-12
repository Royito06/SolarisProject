{{-- Filas de "Asistentes en espera". Se usa en la carga inicial y en cada refresco automático. --}}
@php
    // El rol Visualizador solo puede ver; no se le muestra el botón de validar.
    $puedeValidar = Auth::guard('revisor')->user()->rol !== 'Visualizador';
@endphp
@forelse ($filas as $f)
    <tr class="fila-panel" data-id="{{ $f['id'] }}" data-hash="{{ md5(json_encode($f)) }}">
        <td>{{ $f['nombre'] }}</td>
        <td>{{ $f['fecha_envio'] }}</td>
        @if ($puedeValidar)
            <td><a class="btn btn-primary" href="{{ route('validaciones.edit', $f['id']) }}">Validar</a></td>
        @else
            <td><span class="badge badge-secondary"><i class="fas fa-eye mr-1"></i>Solo lectura</span></td>
        @endif
    </tr>
@empty
    <tr class="fila-vacia"><td colspan="3">No hay alumnos en espera de ser revisados</td></tr>
@endforelse
