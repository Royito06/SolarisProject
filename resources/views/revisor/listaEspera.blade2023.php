@extends('layouts.app_admin')

@section('content')
@php
    //dd($asistentes);
@endphp
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Asistentes a concurso que estan en espera de revision</h1>
                    <hr style="background-color: black;">
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive p-0" style="max-height: 700px;">
                  <table class="table table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Fecha de envio de documento</th>
                        <th>Validar asistente</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if ( count($asistentes) != 0)
                        @foreach ($asistentes as $asistente)
                        <tr>
                          <td>{{$asistente->nombre}}</td>
                          <td>{{$asistente->created_at}}</td>
                          <td><a class="btn btn-primary" href="{{ route('validaciones.edit', $asistente->id) }}"  type="submit">Validar</a></td>
                        </tr>
                        @endforeach
                      @else
                        <td>No hay alumnos en espera de ser revisados</td>
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>

@endsection