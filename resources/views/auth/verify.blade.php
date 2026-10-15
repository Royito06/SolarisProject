@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Favor de confirmar el correo de verificación que hemos enviado a la dirección de correo electrónico que nos proporcionaste. ') }} 
                    <br><i>Revisa la carpeta de correo no deseado o SPAM, si no recibiste el correo en tu bandeja de entrada</i>.
                    <a style="float: right; position: relative; top: 0.5px; margin-left: 2em; font-size: 1em;" href="http://registro.mat.uson.mx:8000/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Enterado
            </a>
                </div>
<!--
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un correo electrónico para verificar su dirección de correo electrónico. ') }}
                        </div>
                    @endif

                     {{ __('Si no recibió el correo electrónico') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('haga clic aquí para solicitar otro ') }}</button>.
                    </form>
                </div>
            -->
            </div>
        </div>
    </div>
</div>
@endsection
