<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.es.js') }}"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}"/>
    <style>
        footer{
            background-color: #007fdc;
        }

        .navbar-brand img{
            width: 300px;
        }

        @media only screen and (max-width: 800px) {
            .navbar-brand img{
                width: 150px;
            }
        }
    </style>
    @yield('header')
</head>
<body class="h-100">

<div id="app" class="d-flex flex-column" style="min-height: 100%;">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/home') }}">
                @guest
                    <img src="{{asset('img/postergris.png')}}"  style="z-index: 2; position: absolute; top: 5px;">
                @else
                    <img src="{{asset('img/postergris.png')}}">
                @endguest
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @guest
                    @else
                        <a class="nav-link" href="{{ url('/home') }}">
                            <i class="far fa-home"></i> Inicio</a>
                            <a class="nav-link" href="{{url('cuenta')}}">
                                <i class="far fa-user"></i> Mi cuenta</a>
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                            @if(\Illuminate\Support\Facades\Auth::user()->inicio_reto!=null)
                                <a class="nav-link" href="{{ url('/reto/cliente') }}"><i class="far fa-running"></i> Actividades</a>
                            @endif
                            <a class="nav-link" href="{{ url('/reto/diario') }}">
                                <i class="far fa-calendar-alt"></i> Programa</a>
                        @else
                            <a class="nav-link" href="{{ url('/reto/imagenes') }}">
                                <i class="far fa-image"></i> Actividades</a>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::ADMIN)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="far fa-cogs"></i> Configuración
                                </a>
                                <div class="dropdown-menu" aria-lab elledby="administracion">
                                    <a class="dropdown-item" href="{{ url('/configuracion/videos') }}">
                                        <i class="far fa-video"></i> Videos</a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/programa') }}">
                                        <i class="far fa-calendar-alt"></i> Programa</a>
                                    <a class="dropdown-item" href="{{ url('/suplementos/') }}">
                                        <i class="far fa-prescription-bottle"></i> Suplementos </a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/contactos') }}">
                                        <i class="far fa-users"></i> Contactos</a>
                                    <a class="dropdown-item" href="{{ url('/usuarios/') }}">
                                        <i class="far fa-clipboard-list"></i> Usuarios</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::ADMIN)
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-poll-h"></i> Encuesta
                            </a>
                            <div class="dropdown-menu" aria-lab elledby="administracion">
                                <a class="dropdown-item" href="{{ url('/encuesta') }}">
                                    <i class="far fa-poll-h"></i> Encuesta</a>
                                <a class="dropdown-item" href="{{ url('/reto/dia/1/0/0') }}">
                                    <i class="far fa-clipboard-list"></i> Dieta 1</a>
                                <a class="dropdown-item" href="{{ url('/reto/dia/32/0/0') }}">
                                    <i class="far fa-clipboard-list"></i> Dieta 2</a>
                            </div>
                        </li>
                        @endif
                        @endguest
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a style="color: #1d68a7" class="nav-link" href="{{ route('login') }}">Miembros</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: #1d68a7" class="nav-link" href="{{ route('register') }}">Registro</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                <i class="far fa-sign-out"></i> Cerrar sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="my-4 d-flex flex-column flex-grow-1 position-relative">
        @if(\Illuminate\Support\Facades\Auth::user()!=null&&\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE &&
                            \Illuminate\Support\Facades\Auth::user()->inicio_reto!=null&&
                            (\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->inicio_reto)->diffInDays(\Carbon\Carbon::now())>intval(env('DIAS'))))
            <div class="container">
                <div class="col-sm-8">
                    <button class="nav-link btn btn-sm btn-warning" data-toggle="modal" data-target="#terminoModal">
                        <i class="far fa-exclamation-triangle"></i> Reto concluído
                    </button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">ATENCIÓN A CLIENTES</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{url('contacto')}}"><i class="fa fa-pencil"></i> Contacto</a></li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">SÍGUENOS EN</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/pipolandin/"><i class="fab fa-facebook-square"></i>
                                Facebook</a></li>
                        <li><a href="https://www.instagram.com/pipolandin/"><i class="fab fa-instagram"></i> Instagram</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">MÉTODOS DE PAGO</h5>
                    <ul class="list-unstyled">
                        <li><i class="fab fa-cc-visa"></i> Visa</li>
                        <li><i class="fab fa-cc-mastercard"></i> Mastercard</li>
                        <li><i class="fab fa-paypal"></i> Paypal</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div class="modal fade" id="terminoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Termino de tus 8 semanas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <video controls poster="{{asset('/img/poster.png')}}">
                        <source src="{{url('/getVideo/termino').'/'.rand(1,100)}}" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#terminoModal').on('show.bs.modal', function (event) {
        let elemento = $('video').first();
        if (elemento.prop("tagName") == "VIDEO") {
            elemento.get(0).play();
        }
    })

    $('#terminoModal').on('hide.bs.modal', function (event) {
        let elemento = $('video').first();
        if (elemento.prop("tagName") == "VIDEO") {
            elemento.get(0).pause();
        }
    })
</script>
@yield('scripts')
</body>
</html>
