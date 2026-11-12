{{-- Filas de "Asistentes inscritos". Se usa en la carga inicial y en cada refresco automático. --}}
@forelse ($filas as $f)
    @php
        $buscar = implode(' | ', [$f['nombre_completo'], $f['folio'], $f['concursos'], 'Inscrito']);
    @endphp
    <tr class="fila-panel" data-id="{{ $f['id'] }}" data-hash="{{ md5(json_encode($f)) }}" data-buscar="{{ $buscar }}">
        <td><p>{{ $f['nombre_completo'] }}</p></td>
        <td><p>{{ $f['folio'] }}</p></td>
        <td><p>{{ $f['concursos'] }}</p></td>
        <td><button value="{{ $f['id'] }}" type="button" class="btn btn-info btn-sm botonInfo" data-toggle="modal" data-target="#staticBackdrop">Info</button></td>
    </tr>
@empty
    <tr class="fila-vacia"><td colspan="4">Todavía no hay alumnos inscritos.</td></tr>
@endforelse
