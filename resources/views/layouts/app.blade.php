<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta Http-Equiv="Cache" content="no-cache">
    <meta Http-Equiv="Pragma-Control" content="no-cache">
    <meta Http-Equiv="Cache-directive" Content="no-cache">
    <meta Http-Equiv="Pragma-directive" Content="no-cache">
    <meta Http-Equiv="Cache-Control" Content="no-cache">
    <meta Http-Equiv="Pragma" Content="no-cache">
    <meta Http-Equiv="Expires" Content="0">
    <meta Http-Equiv="Pragma-directive: no-cache">
    <meta Http-Equiv="Cache-directive: no-cache">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.es.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/7e2ce69f20.js" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}"/>
    <link href="{{asset('css/breathing.css')}}" rel="stylesheet" type="text/css" />
    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    <script type="text/javascript">
        window.CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
    <style>
        @font-face {
            font-family: unitext;
            src: url("{{asset('fonts/unitext thin.ttf')}}");

        }
        #app {
            font-family: unitext;
            font-weight: bold;
            color: black;
        }

        footer{
            background-color: #007fdc;
        }

        .navbar-brand img{
            width: 200px;
        }

        @media only screen and (max-width: 800px) {
            .navbar-brand img{
                width: 120px;
            }
        }

        .tarjeta{
            border-bottom: 2px solid red;
        }
        .badge-warning {
            font-size: 15px;
        }
        .navbar-light .navbar-toggler {
            color: red;
            border: 0px solid;
            border-color: rgba(0, 0, 0, 0.1);
        }
        .navbar {
            padding: 3px !important;
        }
        .navbar-light .navbar-nav .nav-link {
            color: white !important;
            background: #cccccc !important;
            border: 1px solid white !important;
            border-radius: 5px !important;
            font-family: 'Nunito' !important;
            padding-left: 10px;
            font-size: 15px;
        }
        .fa-chevron-down:before {
            padding-left: 10px;
        }
        .dropdown-toggle::after {
            display: none;
        }
        .acton_coins_verde{
            background: #65B32E;
            color: white;
            border-radius: 5px;
            padding: 10px;
        }
        .reto_rojo{
            background: #D30C00;
            color: white;
            border-radius: 5px;
            padding: 10px;
        }
        .aviso_azul{
            background: #29ABE2;
            color: white;
            border-radius: 5px;
            padding: 10px;
        }
        .seguir_blanco{
            background: #ffffff;
            color: #cccccc;
            border-radius: 5px;
            padding: 10px;
        }
        .reaccion_amarillo{
            background: #FFD500;
            color: white;
            border-radius: 5px;
            padding: 10px;
        }
        .dropdown-menu.show {
            padding: 0px !important;
        }

    </style>
    @yield('header')
</head>
<body class="h-100">

<div id="app" class="d-flex flex-column" style="min-height: 100%;">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <div class="row col-12">
                <button class="navbar-toggler col-2" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}" style="height: 50px">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand col-8 offset-1" href="{{ url('/home') }}">
                    @guest
                        <img src="{{asset('images/2021/logo_movil_azul.png')}}" id="imgheader" class="" style="background: transparent !important; margin-top: 5px; margin-bottom: 0px; width: 96%; margin-left: 2%">
                    @else
                        <img src="{{asset('images/2021/logo_movil_azul.png')}}" id="imgheader" class="" style="background: transparent !important; margin-top: 5px; margin-bottom: 0px; width: 96%; margin-left: 2%">
                    @endguest
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @guest
                    @else
                        <!--a class="nav-link" href="{{ url('/home') }}">
                            <i class="far fa-home"></i> Inicio
                        </a-->
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                            @if(\Illuminate\Support\Facades\Auth::user()->tarjeta=='')
                                <!--a class="nav-link" href="{{url('cuenta')}}" title="Es necesario que registres una tarjeta para depositarte tus comisiones">
                                    <i class="fas fa-chevron-down"></i> Mi cuenta
                                </a-->
                            @else
                                <!--a class="nav-link" href="{{url('cuenta')}}">
                                    <i class="fas fa-chevron-down"></i> Mi cuenta
                                </a-->
                            @endif
                        @else
                            <!--a class="nav-link" href="{{url('cuenta')}}">
                                <i class="fas fa-chevron-down"></i> Mi cuenta
                            </a-->
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                            @if(\Illuminate\Support\Facades\Auth::user()->inicio_reto!=null)
                                <!--a class="nav-link" href="{{ url('/reto/cliente') }}"><i class="far fa-running"></i> Mi Vida Fit</a-->
                            @endif
                            <a class="nav-link" href="{{ url('/reto/programa') }}">
                                <i class="fas fa-chevron-down"></i> Mi Plan Acton</a>
                            <a class="nav-link" href="{{ url('/usuarios/referidos_top') }}">
                                <i class="fas fa-chevron-down"></i> Top Referidos</a>
                        @else
                            <!--a class="nav-link" href="{{ url('/reto/configuracion') }}">
                                <i class="fas fa-chevron-down"></i> Mi Vida Fit</a-->
                        @endif
                        <!--a class="nav-link" href="{{ url('/usuarios/seguir') }}">
                            <i class="fas fa-chevron-down"></i> Personas</a-->
                        @if(\Illuminate\Support\Facades\Auth::user()!=null && \Illuminate\Support\Facades\Auth::user()->vencido)
                            <!--button id="breathPC" class="nav-link btn btn-sm btn-warning ld x2 ld-breath" data-toggle="modal" data-target="#terminoModal">
                                <i class="far fa-exclamation-triangle"></i>
                                <span>Reto conclu??do</span>
                                <br>
                                <span class="small">(Ver video)</span>
                            </button-->
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::ADMIN)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-chevron-down"></i> Configuraci??n
                                </a>
                                <a class="nav-link" href="{{ url('/usuarios/referidos_top') }}">
                                    <i class="fas fa-chevron-down"></i> Top Referidos</a>
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
                                    <a class="dropdown-item" href="{{ url('/usuarios/usuarios_gratis/') }}">
                                        <i class="far fa-clipboard-list"></i> Usuarios Gratis</a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/registro-usuario') }}">
                                        <i class="fas fa-clipboard-list"></i> Alta de Usuarios</a>
                                    <a class="dropdown-item" href="{{ url('/usuarios/usuarios_validar') }}">
                                        <i class="fas fa-clipboard-list"></i> Validacion de Usuarios</a>
                                    <a class="dropdown-item" href="{{ url('/usuarios/registro-tiendas') }}">
                                        <i class="fas fa-dumbbell"></i> Tiendas/GYM</a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/registro-coach') }}">
                                        <i class="fas fa-dumbbell"></i> Entrenadores</a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/generar-codigo') }}">
                                        <i class="fas fa-hashtag"></i> Generar codigo</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::COACH)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-chevron-down"></i> Configuraci??n
                                </a>
                                <div class="dropdown-menu" aria-lab elledby="administracion">
                                    <a class="dropdown-item" href="{{ url('/configuracion/videos_coach') }}">
                                        <i class="far fa-video"></i> Videos</a>
                                    <a class="dropdown-item" href="{{ url('/configuracion/usuarios_coach/') }}">
                                        <i class="far fa-clipboard-list"></i> Usuarios</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::TIENDA)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-chevron-down"></i> Configuraci??n
                                </a>
                                <div class="dropdown-menu" aria-lab elledby="administracion">
                                    <a class="dropdown-item" href="{{ url('/configuracion/generar-codigo') }}">
                                        <i class="fas fa-hashtag"></i> Generar codigo</a>
                                </div>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::ADMIN)
                            <!--li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-chevron-down"></i> Encuesta
                                </a>
                                <div class="dropdown-menu" aria-lab elledby="administracion">
                                    <a class="dropdown-item" href="{{ url('/encuesta') }}">
                                        <i class="far fa-poll-h"></i> Encuesta</a>
                                    <a class="dropdown-item" href="{{ url('/reto/dia/1/0/0') }}">
                                        <i class="far fa-clipboard-list"></i> Dieta 1</a>
                                    <a class="dropdown-item" href="{{ url('/reto/dia/15/0/0') }}">
                                        <i class="far fa-clipboard-list"></i> Dieta 2</a>
                                    <a class="dropdown-item" href="{{ url('/reto/dia/29/0/0') }}">
                                        <i class="far fa-clipboard-list"></i> Dieta 3</a>
                                    <a class="dropdown-item" href="{{ url('/reto/dia/43/0/0') }}">
                                        <i class="far fa-clipboard-list"></i> Dieta 4</a>
                                </div>
                            </li-->
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i> Avisos
                            </a>
                            <div class="dropdown-menu" aria-lab elledby="administracion">
                                @yield('notificaciones')
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if (auth()->user()->unreadnotifications) animate__heartBeat @endif" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i> Mensajes @if (auth()->user()->unreadnotifications)<span class="badge badge-warning">{{ \App\Notifications::where('data', 'like', '%"usuario_receptor_id":"'.auth()->user()->id.'%')->where('type', 'App\Notifications\MensajeNotification')->whereNull('read_at')->count() }}</span>@endif
                            </a>
                            <div class="dropdown-menu" aria-lab elledby="administracion">
                                <a class="dropdown-item" href="{{ url('/configuracion/mensajes') }}">
                                    <i class="far fa-comment"></i> Mensajes
                                    <span class="float-right text-muted text-sm" style="padding-right: 5px;">Mensajes</span>
                                </a>
                                @foreach(auth()->user()->unreadNotifications as $notification)
                                    @if($notification->type == 'App\Notifications\MensajeNotification')
                                    <a class="dropdown-item" href="{{ url('/configuracion/mensaje-directo/'.$notification->data['usuario_emisor_id']) }}">
                                        @if(isset($notification->data['usuario_emisor_id']))
                                            <i class="far fa-comment"></i> {{ \App\User::where(['id' => $notification->data['usuario_emisor_id']])->pluck('name')->first() }} {{ \App\User::where(['id' => $notification->data['usuario_emisor_id']])->pluck('last_name')->first() }}
                                            <span class="float-right text-muted text-sm" style="padding-right: 5px;">{{ $notification->created_at->diffForHumans() }}</span>
                                        @endif
                                    </a>
                                    @endif
                                @endforeach
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    @empty
                                    <div class="dropdown-item">Sin notificaciones</div>
                                @endforelse
                                <a class="dropdown-item dropdown-footer" href="{{ url('markAsRead') }}">
                                <i class="far fa-comment"></i> Marcar como leido </a>

                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i> Estado de Cuenta
                            </a>
                            <div class="dropdown-menu" aria-lab elledby="administracion">
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ url('/usuarios/referidos') }}">
                                <i class="fas fa-chevron-down"></i> Mis Referidos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-chevron-down"></i> Solicitudes de Retos
                            </a>
                            <div class="dropdown-menu" aria-lab elledby="administracion">
                            </div>
                        </li>
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
                                <i class="far fa-sign-out"></i> Cerrar sesi??n
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
    <main class="d-flex flex-column flex-grow-1 position-relative">


        @if(\Illuminate\Support\Facades\Auth::user()!=null && \Illuminate\Support\Facades\Auth::user()->vencido)
            <!--div class="container" id="breathMovil">
                <button class="nav-link btn btn-sm btn-warning ld ld-breath ml-auto mr-auto" data-toggle="modal" data-target="#terminoModal">
                    <i class="far fa-exclamation-triangle"></i>
                    <span>Reto conclu??do</span>
                    <br>
                    <span class="small">(Ver video)</span>
                </button>
            </div-->
        @endif
        @yield('content')
    </main>
    <!--footer>
        <div class="container">
            <div class="row col-sm-10">
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">ATENCI??N A CLIENTES</h5>
                    <ul class="list-unstyled">
                        @if(\Illuminate\Support\Facades\Auth::guest())
                            <li><a href="{{url('contacto')}}"><i class="fa fa-pencil"></i> Dudas</a></li>
                        @else
                            <li><a href="{{url('dudas')}}"><i class="fa fa-pencil"></i> Dudas</a></li>
                        @endif
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">S??GUENOS EN</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.facebook.com/FitnessPipoLandin"><i class="fab fa-facebook-square"></i>
                                Facebook</a></li>
                        <li><a href="https://www.instagram.com/pipolandin/"><i class="fab fa-instagram"></i> Instagram</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-3">
                    <h5 class="font-weight-bold">M??TODOS DE PAGO</h5>
                    <ul class="list-unstyled">
                        <li><i class="fab fa-cc-visa"></i> Visa</li>
                        <li><i class="fab fa-cc-mastercard"></i> Mastercard</li>
                        <li><i class="fab fa-paypal"></i> Paypal</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer-->


    <div class="modal fade" id="terminoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Gracias. Te veo muy pronto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(\Illuminate\Support\Facades\Auth::user()!=null&&\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE &&
                            \Illuminate\Support\Facades\Auth::user()->inicio_reto!=null&&
                            (\Carbon\Carbon::parse(\Illuminate\Support\Facades\Auth::user()->inicio_reto)->diffInDays(\Carbon\Carbon::now())+1>intval(env('DIAS'))))
                        <video controls poster="{{asset('/img/poster.png')}}">
                            <source src="{{url('/getVideo/termino').'/'.rand(1,100)}}" type="video/mp4">
                        </video>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
<script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
<script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
<script src="https://unpkg.com/@chenfengyuan/vue-qrcode@1.0.1/dist/vue-qrcode.min.js"></script>

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
    // device detection
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
        $("#breathPC").hide();
    }else{
        $("#breathMovil").hide();
    }
</script>
@yield('scripts')
</body>
</html>
