<!--archivo para deshabilitar inscripciones...solo actualizaciones!-->
<div class="top-header">
    <div class="container">
        @auth

            <a class="btn btn-outline-light btn-sm lo-btn" style="float: right; position: relative; top: 0.5px; margin-left: 2em; font-size: 1em;"  href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                {{ __('Salir') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        @endauth

        <a href="https://twitter.com/Concursofismat"><span class="icon-tt"></span></a>
        <a href="https://www.facebook.com/Concursofismat/"><span class="icon-fb"></span></a>
    </div>
</div>

<div class="container center-header">
    <div class="row">
        <div class="col-md-2 col-sm-2 col-xs-2">
            <img src="/img/logo_uni.jpg" style="position: relative; margin-top: 0.5em;" alt="">
        </div>
        <div class="col-md-10 col-sm-10 col-xs-10 ban-tit">
            <h1 id="tit" style="text-align: left;">Concurso Regional de Física y Matemáticas</h1>
            <h2 id="sub" style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Departamento de Física y Departamento de Matemáticas</h2>
            <h4 style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inscripciones cerradas</strong></ins></h4>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-md navbar-light shadow-sm">
    <div class="container" style="background-color: #f7a11a" >
        <!--
        <a class="txt-brand" id="tab-inicio" href="{{ url('/') }}">Inicio</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            {{--<ul class="navbar-nav mr-auto">

            </ul> --}}

            <!-- Right Side Of Navbar -->
            {{-- ml-auto --}}
            <ul class="navbar-nav mr-auto">
                <!-- Authentication Links -->
                @guest
                <!--//HABILITAR O DESAHABILITAR LA PARTE DE REGISTRO, ASI COMO DE LOGIN-->
                @if (Route::has('register'))
                <!--
                        <li class="nav-item">
                            <a class="txt-brand" id="tab-register" href="{{ route('register') }}">{{ __('Registro') }}</a>
                        </li>
                -->
                    @endif
<!--            
                    <li class="nav-item">
                        <a class="txt-brand"  id="tab-login"  href="{{ route('login') }}">{{ __('Acceso') }}</a>
                    </li>
-->
                @else
                <!--
                    <li class="nav-item">
                        <a class="txt-brand" id="tab-asistentes"  href="{{ route('asistentes.index') }}">{{ __('Perfil') }}</a>
                    </li>
-->
                @endguest
            </ul>
        </div>
    </div>
</nav>
