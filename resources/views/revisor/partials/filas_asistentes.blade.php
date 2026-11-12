{{-- Filas de "Todos los asistentes". Se usa en la carga inicial y en cada refresco automático. --}}
@php
    $etiquetasEstado = ['Espera' => 'Espera', 'Rechazado' => 'Renvío', 'Aprobado' => 'Inscrito'];
@endphp
@forelse ($filas as $f)
    @php
        $estadoTxt    = $f['estado'] === null ? 'Enviando' : ($etiquetasEstado[$f['estado']] ?? $f['estado']);
        $folioTxt     = $f['folio'] ?: 'Alumno sin número de folio';
        $escuelaTxt   = $f['escuela'] ?: 'Sin escuela registrada';
        $concursosTxt = $f['estado'] === null ? 'Sin concursos' : $f['concursos'];
        $asesorTxt    = $f['asesor'] ?: 'Sin asesor';
        $buscar       = implode(' | ', [$f['nombre_completo'], $folioTxt, $escuelaTxt, $concursosTxt, $estadoTxt]);
    @endphp
    <tr class="fila-panel" data-id="{{ $f['id'] }}" data-hash="{{ md5(json_encode($f)) }}" data-buscar="{{ $buscar }}">
        <td><p>{{ $f['nombre_completo'] }}</p></td>
        <td><p>{{ $folioTxt }}</p></td>
        <td><p>{{ $escuelaTxt }}</p></td>
        <td><p>{{ $concursosTxt }}</p></td>
        <td><p>{{ $estadoTxt }}</p></td>
        <td><button value="{{ $f['id'] }}" type="button" class="btn btn-info btn-sm botonInfo" data-toggle="modal" data-target="#staticBackdrop">Info</button></td>
        <td><p>{{ $asesorTxt }}</p></td>
    </tr>
@empty
    <tr class="fila-vacia"><td colspan="7">Todavía no hay asistentes registrados.</td></tr>
@endforelse
