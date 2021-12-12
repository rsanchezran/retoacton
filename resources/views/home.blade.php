@extends('layouts.app')
@section('header')

    @if(\Illuminate\Support\Facades\Auth::user()->primer_inicio == 0)
        @php
        {{  header("Location: /cuenta"); }}
        {{  die();    }}
        @endphp
    @endif

    <style>
        hr{
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .money{
            margin-left: 5px;
        }
        .contenedor{
            width: 100% !important;
        }
        .estas_listo img{
            margin-top: -55px;
            margin-left: 20px;
        }
        .estas_listo div{
            padding-top: 10px;
            display: inline-block;
        }
        .estas_listo{
            background-color: rgba(0, 159, 227, 1);
            color: white;
            height: 150px;
            font: caption;
        }
        .planes .col-sm-3{
            padding: 10px;
        }
        .planes{
            background-color: rgba(0, 93, 156, 1);
            width: 100%;
            height: auto;
        }
        #pagar{
            width: 25% !important;
        }
        .card-img-top {
            width: 70% !important;
            margin-left: 15% !important;
            padding-top: 25px !important;
            padding-bottom: 42px !important;
        }

        .imagenpersonal img {
            display:block;
            margin:0 -10px;
        }

        .card {
            border: 0px solid rgba(0, 0, 0, 0.125) !important;
        }

        .cardbusqueda {
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .usuario {
            padding: 10px;
            border-bottom: 1px solid #c2c2c2;
        }

        .subir_foto{
            background: #007FDC;
            color: white;
            border: 0px solid;
            border-radius: 5px;
            padding: 5px;
        }
        .mialbum_card{
            border: 1px solid #c2c2c2 !important;
            padding: 5px;
            border-radius: 5px;
        }
        .flechas-right{
            background: transparent !important;
            border: 0px solid;
            margin-top: 45%;
            position: absolute;
            margin-left: 82%;
            z-index: 1000;
            color: white;
        }
        .flechas-left{
            background: transparent !important;
            border: 0px solid;
            margin-top: 45%;
            z-index: 1000;
            position: absolute;
            margin-left: 0%;
            color: white;
        }
        .modal-body {
            padding: 0 !important;
        }
        .modal-body img {
            padding: 0 !important;
        }
        .trespuntos{
            position: absolute;
            z-index: 9999999999999999999;
            margin-top: 5%;
            width: 7% !important;
            margin-left: -10%;
        }
        .modal_mini {
            position: absolute;
            z-index: 9999999999999999999;
            margin-top: -90%;
            width: 100%;
        }
        #accordionExample2 {
            position: absolute;
            margin-top: -100%;
            width: 100%;
            background: #CCCCCC;
        }
        #accordionExample2 .card-header {
            color: #fff;
            background-color: #cccccc;
            border: 1px solid;
        }
        @media only screen and (max-width: 800px) {
            #pagar {
                width: 80% !important;
            }
            .estas_listo{
                height: 300px;
            }
            .estas_listo img{
                margin-top: -5px;
            }
            .controls-top{
                display: none;
            }
            .carousel-indicators li {
                width: 8px !important;
                height: 6px !important;
                border-radius: 50% !important;
            }
            .copa{
                width: 60% !important;
            }

            .imagenpersonal img {
                display:block;
                margin:0 0px;
                width: 400px !important;
            }
            .lunes-proximo{
                border: 0.6px solid #c2c2c2;
                border-radius: 5px;
                min-height: 100px;
                color: #c2c2c2;
            }
        }
        .modal_reto{
            padding: 20px;
            position: absolute;
            margin: 0;
            width: 98%;
            background: #D30C00 !important;
            color: white;
            height: 95vh;
            position: fixed;
            z-index: 10000;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        .modal_coins{
            padding: 20px;
            position: absolute;
            margin: 0;
            width: 98%;
            background: #65B32E !important;
            color: white;
            height: 95vh;
            position: fixed;
            z-index: 10000;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        .modal_reacciones{
            padding: 20px;
            position: absolute;
            margin: 0;
            width: 98%;
            background: #FFD500 !important;
            color: white;
            height: 95vh;
            position: fixed;
            z-index: 10000;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }
        .countdown-reto{
            font-family: 'Nunito';
            font-size: 23px;
        }
        .image-upload>input {
            display: none;
        }
        /*.close-top {
            position: relative;
            top: -10px;
            right: -319px;
            font-size: 25px;
        }*/
        .close-top {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 25px;
        }
        .retos_div{
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 20px;
            margin-left: 0px;
            padding: 10px;
            box-shadow: 2px 2px rgba(0,0,0,.125);
        }
        .btn-link:hover {
            color: white !important;
            text-decoration: none !important;
        }
        .btnagregar{
            background: #0080DD !important;
            border: 1px solid #0080DD;
            color: white;
        }
        .tarjeta_precios{
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 20px;
            margin-left: 10px;
            padding: 10px;
            box-shadow: 2px 2px 10px 2px rgb(0 0 0 / 13%);
            margin-right: 10px;
            color: #666666;
            font-size: 0.7em;
        }
        .tarjeta_precios table tr {
            border-bottom: 1px solid #d2d2d2;
        }
        .tarjeta_precios table td{
            padding-bottom: 5px;
            padding-top: 5px;
        }
        .tarjeta_precios table tr:last-child {
            border-bottom: 0px solid #d2d2d2;
        }

    </style>
@endsection
@section('notificaciones')
    @foreach(auth()->user()->unreadNotifications as $notification)
        @if($notification->type == 'App\Notifications\CoinsNotification')
            <a class="dropdown-item acton_coins_verde" onclick='var l = document.getElementById("{{$notification->id}}");for(var i=0; i<5; i++){l.click();}'>
                <img src="{{asset('images/2021/moneda_mini.png')}}" class="mr-2 ml-2" width="6%"> Obtuviste Acton Coins</a>
        @endif
    @endforeach
    @foreach(auth()->user()->unreadNotifications as $notification)
        @if($notification->type == 'App\Notifications\RetosNotification')
            <div class="dropdown-item reto_rojo"  onclick='var l = document.getElementById("{{$notification->id}}");for(var i=0; i<5; i++){l.click();}'>
                <img src="{{asset('images/2021/rayo_mini.png')}}" class="mr-2 ml-2" width="6%"> Tienes un reto</div>
        @endif
    @endforeach
    @foreach(auth()->user()->unreadNotifications as $notification)
        @if($notification->type == 'App\Notifications\AvisoNotification')
            <a class="dropdown-item aviso_azul" onclick='var l = document.getElementById("{{$notification->id}}");for(var i=0; i<5; i++){l.click();}'>
                <img src="{{asset('images/2021/a_acton.png')}}" class="mr-2 ml-2" width="6%"> Reto Acton te mando un aviso</a>
        @endif
    @endforeach
    @foreach(auth()->user()->unreadNotifications as $notification)
        @if($notification->type == 'App\Notifications\SeguirNotification')
            <a class="dropdown-item seguir_blanco" onclick='var l = document.getElementById("{{$notification->id}}");for(var i=0; i<5; i++){l.click();}'>
                <img src="{{asset('images/2021/fulanito.png')}}" class="mr-2 ml-2" width="6%"> Te comenzo a seguir</a>
        @endif
    @endforeach
    @foreach(auth()->user()->unreadNotifications as $notification)
        @if($notification->type == 'App\Notifications\ReaccionesNotification')
            <a class="dropdown-item reaccion_amarillo" onclick='var l = document.getElementById("{{$notification->id}}");for(var i=0; i<5; i++){l.click();}'>
                <img src="{{asset('images/2021/like_acton.png')}}" class="mr-2 ml-2" width="6%"> reaccionó a tu foto</a>
        @endif
    @endforeach
@endsection
@section('content')
    <div id="vue" class="flex-center">
        <inicio :usuario="{{ $usuario}}" :referencias="{{$referencias}}" :monto="{{$monto}}" :descuento="{{$descuento}}"
                :original="{{$original}}" :saldo="{{$saldo}}" :fotos="{{$fotos}}" :retos="{{$retos}}" :seguidos="{{$seguidos}}" :siguen="{{$siguen}}"></inicio>
    </div>

    <template id="inicio-template">
        <div class="">


            <!--div class="card-header">Hola, @{{ usuario.name }}</div-->
            <div class="" style="">
            @if (session('status'))
                <!--div class="alert alert-success" role="alert">
                                {{ session('status') }}
                        </div-->
                @endif
                <div style="">
                    <div class="" align="center" style="">
                        <!--div :src="'{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id.'/'.rand(0,1970))}}'"-->
                        <div  class="NO-CACHE" src="{{asset('users/'.\Illuminate\Support\Facades\Auth::user()->id.'.png')}}"
                             width="100%" style=" min-height: 300px;" :style="{
                                    height: '100px',
                                    backgroundColor: '#323232',
                                    backgroundImage: 'url(\'' + imagen_perfil + '\')',
                                    backgroundPosition: 'center center',
                                    backgroundSize: 'cover'
                                    }"></div>
                        <br>
                    </div>

                </div>
                <div class="row col-12">
                    <div class="col-10">
                        <h2>@{{ usuario.name }} @{{ usuario.last_name }}</h2>
                    </div>
                    <div class="col-2" style="margin-top: 10px;">
                        <a href="/cuenta"><i class="fas fa-pen"></i></a>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <div class="col-12">
                        <br>
                        <h6>Tu código es: {{\Illuminate\Support\Facades\Auth::user()->referencia}}</h6>
                        <qrcode :value="'https://retoacton.com/registro/gratis/?codigo={{\Illuminate\Support\Facades\Auth::user()->referencia}}'" :options="{ width: 200 }" ></qrcode>
                        <br>
                    </div>
                    <div class="col-12">
                        <h6 class="">Cuentas con:</h6>
                        <h4 class="acton" style="color:#007FDC;"><money :cantidad="''+usuario.saldo"></money></h4>
                        <h6>Acton coins</h6>
                        <div style="display: none">
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @if($notification->type == 'App\Notifications\CoinsNotification')
                                <a class="dropdown-item acton_coins_verde" id="{{$notification->id}}"
                                   @click="mostrarminicoins('{{$notification->data['monto']}}', '{{$notification->data['tipo_compra']}}', '{{ \App\MiAlbum::where(['id' => $notification->data['referencia']])->pluck('archivo')->first() }}', '{{ \App\Retos::where(['id' => $notification->data['referencia']])->pluck('descripcion')->first() }}')">
                                    <img src="{{asset('images/2021/moneda_mini.png')}}" class="mr-2 ml-2" width="6%"> Obtuviste Acton Coins</a>
                            @endif
                        @endforeach
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @if($notification->type == 'App\Notifications\RetosNotification')
                                <div class="dropdown-item reto_rojo" id="{{$notification->id}}"
                                     @click="mostrarmini('{{ \App\User::where(['id' => $notification->data['usuario_reta_id']])->pluck('name')->first() }} {{ \App\User::where(['id' => $notification->data['usuario_reta_id']])->pluck('last_name')->first() }}', '{{$notification->data['coins']}}', '{{$notification->data['descripcion']}}', '{{$notification->created_at}}', '{{$notification->data['publico']}}', 'reto', '{{$notification->id}}', '{{$notification->data['id']}}', '{{ \App\Retos::where(['id' => $notification->data['id']])->pluck('aceptado')->first() }}', '{{ \App\Retos::where(['id' => $notification->data['id']])->pluck('updated_at')->first() }}', '{{ \App\Retos::where(['id' => $notification->data['id']])->pluck('video')->first() }}')">
                                    <img src="{{asset('images/2021/rayo_mini.png')}}" class="mr-2 ml-2" width="6%"> Tienes un reto</div>
                            @endif
                        @endforeach
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @if($notification->type == 'App\Notifications\AvisoNotification')
                                <a class="dropdown-item aviso_azul" id="{{$notification->id}}">
                                    <img src="{{asset('images/2021/a_acton.png')}}" class="mr-2 ml-2" width="6%"> Reto Acton te mando un aviso</a>
                            @endif
                        @endforeach
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @if($notification->type == 'App\Notifications\SeguirNotification')
                                <a class="dropdown-item seguir_blanco" id="{{$notification->id}}">
                                    <img src="{{asset('images/2021/fulanito.png')}}" class="mr-2 ml-2" width="6%"> Te comenzo a seguir</a>
                            @endif
                        @endforeach
                        @foreach(auth()->user()->unreadNotifications as $notification)
                            @if($notification->type == 'App\Notifications\ReaccionesNotification')
                                <a class="dropdown-item reaccion_amarillo" id="{{$notification->id}}"
                                   @click="mostrarminireacciones('{{ \App\User::where(['id' => $notification->data['usuario_like_id']])->pluck('name')->first() }} {{ \App\User::where(['id' => $notification->data['usuario_like_id']])->pluck('last_name')->first() }}', '{{ \App\MiAlbum::where(['id' => $notification->data['album_id']])->pluck('archivo')->first() }}', '{{$notification->data['tipo_like']}}')">
                                    <img src="{{asset('images/2021/like_acton.png')}}" class="mr-2 ml-2" width="6%"> reaccionó a tu foto</a>
                            @endif
                        @endforeach
                        </div>
                    </div>
                    <br>
                    <div v-if="minutos>0" class="col-12 lunes-proximo">
                        <br>
                        <div class="col-12"><h6 class="">Tu reto empieza en:</h6></div>
                        <br>
                        <div class="row col-12">
                            <div class="col-3">
                                @{{ dias_hasta }}<br>
                                Días
                            </div>
                            <div class="col-3">
                                @{{ horas }}<br>
                                Horas
                            </div>
                            <div class="col-3">
                                @{{ minutos }}<br>
                                Minutos
                            </div>
                            <div class="col-3">
                                @{{ segundos }}<br>
                                Segundos
                            </div>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <!--a href="#" @click="obtenercoins" class="col-6"><img src="{{asset('images/2021/obtener_semanas.png')}}" class="col-8"></a-->
                        <a href="/cuenta/massemanas/1" class="col-6"><img src="{{asset('images/2021/obtener_semanas.png')}}" class="col-8"></a>
                    </div>
                </div>
            </div>

            <br>
            <br>
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-sort-down mr-1"></i> Mi información
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row col-12">
                                <div class="col-6">
                                    <h2><a href="usuarios/seguir/?q=siguen#lstUsuarios">@{{siguen}}</a></h2>
                                    Seguidores
                                </div>
                                <div class="col-6 mb-4">
                                    <h2><a href="usuarios/seguir/?q=seguidos#lstUsuarios">@{{seguidos}}</a></h2>
                                    Seguidos
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.edad_publico">
                                    <img src="{{asset('images/2021/edad_info.png')}}" width="30px" class="mr-2">@{{ usuario.edad }} Años
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.idiomas_publico">
                                    <img src="{{asset('images/2021/idiomas.png')}}" width="30px" class="mr-2">@{{ usuario.idiomas }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.gym_publico">
                                    <img src="{{asset('images/2021/gym_info.png')}}" width="30px" class="mr-2">@{{ usuario.gym }}
                                </div>
                                <div class="col-12 mb-2">
                                    <img src="{{asset('images/2021/info_ubicacion.png')}}" width="30px" class="mr-2">@{{ usuario.estado }}, @{{ usuario.ciudad }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.idiomas_publico">
                                    <img src="{{asset('images/2021/intereses_info.png')}}" width="30px" class="mr-2">@{{ usuario.idiomas }}
                                </div>
                                <div class="col-12 mb-2" v-if="usuario.situacion_actual_publico">
                                    <img src="{{asset('images/2021/estado_info.png')}}" width="30px" class="mr-2">@{{ usuario.situacion_actual }}
                                </div>
                                <div class="col-12 mt-4 text-center">
                                    <a href="usuarios/seguir" class="col-12"><img class="w-100" src="{{asset('images/2021/haz_match.png')}}"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-sort-down mr-1"></i> Mi álbum
                            </button>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body text-center">
                            <button v-tooltip="{content:'Nueva Foto'}" class="btn btn-sm btn-default" @click="sigue()" style="border: 0px solid;">
                                <img src="{{asset('images/2021/agregar_video.png')}}" class="w-50 mb-3">
                            </button>
                            <div class="row text-center">
                                <div v-for="f in fotos" class="text-center col-4 ">
                                    <a class="thumbnail" href="#" :data-image-id="f.id" data-toggle="modal" data-title=""
                                       :data-image="f.archivo"
                                       :id="f.id"
                                       :data-img-id="f.id"
                                       :data-descripcion="f.descripcion"
                                       data-target="#image-gallery"
                                       @click="interacciones"
                                    >
                                        <img :src="f.archivo" class="col-12 mialbum_card">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div v-if="ocultareto" class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-sort-down mr-1"></i> Mis retos
                            </button>
                        </h5>
                    </div>
                    <div v-if="ocultareto" id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="row text-center">
                                <div v-for="f in retos" class="text-center col-12 text-info">
                                    <!--a class="thumbnail" href="#" :data-image-id="f.id" data-toggle="" data-title=""
                                       @click="mostrarmini('usuario.name', f.coins, f.descripcion, f.created_at, f.publico, 'reto', f.id, f.id,  f.aceptado, f.updated_at,  f.video)"
                                    -->
                                    <div class="row col-12 mt-2 retos_div">
                                        <div class="col-4">
                                            <img @click="mostrarmini('usuario.name', f.coins, f.descripcion, f.created_at, f.publico, 'reto', f.id, f.id,  f.aceptado, f.updated_at,  f.video)" src="{{asset('images/2021/video_prev.png')}}" class="w-100">
                                        </div>
                                        <div class="col-8 text-left text-secondary">
                                            <label>El reto consiste en:</label>
                                            <br/>
                                            <label>@{{ f.descripcion }}</label>
                                            <br/>
                                            <div class="row col-12 text-left">
                                                <img src="{{asset('images/2021/moneda_mini.png')}}" class="mt-1 mb-1" style="width: 40px; width: 35px; height: 35px; margin-left: -15px; margin-right: 10px;">
                                                <h5 class=" mt-2 mb-1">@{{ f.coins }} Acton Coins</h5>
                                            </div>
                                        </div>
                                        <div class="row text-left col-12">
                                        </div>
                                        <div class="text-center col-12">
                                            <img @click="mostrarmini(usuario.name, f.coins, f.descripcion, f.created_at, f.publico, 'reto', f.id, f.id,  f.aceptado, f.updated_at,  f.video)" src="{{asset('images/2021/ver_reto.png')}}" class="col-6">
                                        </div>
                                    </div>
                                    <!--/a-->
                                </div>
                        </div>
                    </div>
                </div>
                <!--div class="card">
                    <div class="card-header" id="headingThree">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-sort-down mr-1"></i> Intercambios
                            </button>
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                        </div>
                    </div>
                </div-->
            </div>

        <!--br>
                    <br>
                    <h4 class="" style="color:#007FDC;">Edad: @{{ usuario.edad }}</h4>
                    <h4 class="" style="color:#007FDC;">Empleo: @{{ usuario.empleo }}</h4>
                    <h4 class="" style="color:#007FDC;">Estudios: @{{ usuario.estudios }}</h4>
                    <h4 class="" style="color:#007FDC;">Intereses: @{{ usuario.intereses }}</h4>
                    <h4 class="" style="color:#007FDC;">Idiomas: @{{ usuario.idiomas }}</h4>
                    <h4 class="" style="color:#007FDC;">Gym: @{{ usuario.gym }}</h4>
                    <br>
                    <h4 class="" style="color:#007FDC;">@{{ usuario.estado }}, @{{ usuario.ciudad }}, @{{ usuario.colonia }}</h4>
                    @if(\Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::TIENDA && \Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::COACH && \Illuminate\Support\Facades\Auth::user()->rol!==\App\Code\RolUsuario::ENTRENADOR)
            <a-- v-if="usuario.inicio_reto==null" class="btn btn-lg btn-primary" href="{{url('/reto/comenzar/')}}">
                        <span>EMPEZAR RETO</span>
                    </a-->
        @endif
        <!--a v-else class="btn btn-lg btn-primary" href="{{url('/reto/programa')}}">
                                <span>Mi programa</span>
                            </a-->
        <!--br>
                    <br>
                    <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank"-->
            <!--i class="fa fa-file-pdf"></i> Descarga aquí tu manual de apoyo-->
            <!--/a-->





        @if(\Illuminate\Support\Facades\Auth::user()->vencido)
            <!--div class="card col-md-5 d-block ml-auto mr-auto text-center">
                        <img src="{{asset('/images/imagesremodela/copa.png')}}" width="45%" class="copa">
                </div-->
            @endif


            <div class="card">
            @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                <!--div class="">
                                <div class="">
                                    <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                                        <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                               estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                               :caracter="true"></money>
                                    </label>



                                    <div class="estas_listo text-center">
                                        <div class="row" style="width: 100%;">
                                            <div class="col-sm-12">
                                                <div>
                                                    <h1>¿Estas listo para elegir<br>
                                                        tu plan de seguimiento?</h1>
                                                </div>
                                                <div>
                                                    <img src="{{asset('/images/imagesremodela/tablaok.png')}}" width="50">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="planes text-center flex-column d-none d-md-block d-lg-block">
                                        <div class="row col-sm-11" style="margin-left: 4%;padding-top: 20px;padding-bottom: 20px;">
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/2semanasrenovar.png')}}" width="95%" @click="diasChange(14)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/4semanasrenovar.png')}}" width="95%" @click="diasChange(28)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/8semanasrenovar.png')}}" width="95%" @click="diasChange(56)">
                                            </div>
                                            <div class="col-sm-3">
                                                <img src="{{asset('/images/imagesremodela/12semanasrenovar.png')}}" width="95%" @click="diasChange(84)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="planes text-center flex-column d-none d-sm-block d-block d-md-none">


                                        <div id="multi-item-example" class="carousel slide carousel-multi-item" data-ride="carousel">

                                            <div class="controls-top">
                                                <a class="btn-floating" href="#multi-item-example" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
                                                <a class="btn-floating" href="#multi-item-example" data-slide="next"><i class="fa fa-chevron-right"></i></a>
                                            </div>

                                            <ol class="carousel-indicators">
                                                <li data-target="#multi-item-example" data-slide-to="0" class="active"></li>
                                                <li data-target="#multi-item-example" data-slide-to="1"></li>
                                                <li data-target="#multi-item-example" data-slide-to="2"></li>
                                                <li data-target="#multi-item-example" data-slide-to="3"></li>
                                            </ol>

                                            <div class="carousel-inner" role="listbox">

                                                <div class="carousel-item active">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/2semanasrenovar.png')}}" width="50%" @click="diasChange(14)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/4semanasrenovar.png')}}" width="50%" @click="diasChange(28)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/8semanasrenovar.png')}}" width="50%" @click="diasChange(56)">
                                                    </div>

                                                </div>
                                                <div class="carousel-item ">

                                                    <div class="row">
                                                        <img class="card-img-top" src="{{asset('/images/imagesremodela/12semanasrenovar.png')}}" width="50%" @click="diasChange(84)">
                                                    </div>

                                                </div>



                                            </div>

                                        </div>


                                    </div>
                                    <br>


                                    <div id="infoPago" v-if="descuento>0">
                                        <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                                            el </label>
                                        <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                                        <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive">ÚLTIMO DIA</label>
                                    </div>
                                    <div id="pagar" class="text-center" style="widows: 13% !important; color: black;">
                                        <div>
                                            <img src="{{asset('/images/imagesremodela/medalla.png')}}" width="95%">
                                        </div>
                                        <div style="font-size: 1.5rem;margin-left: 10%;">
                                                <input
                                                        type="checkbox"
                                                        :value="saldochk"
                                                        id="saldochk"
                                                        v-model="saldochk"
                                                        @change="check($event)">
                                                Usar saldo<br>
                                                a sólo<br>
                                            <money :cantidad="''+montopago" :caracter="true" :decimales="0"
                                                   estilo="font-size:1.5em; font-weight: bold"></money>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div>
                                        <button id="pagarceros" @click="pagaRefrendo" class="btn btn-primary col-md-4">Pagar</button>
                                    </div>
                                    <cobro ref="cobro" :cobro="''+montopago" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                                           :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                                           @terminado="terminado"></cobro>
                                </div>
                            </div>
                            <hr-->
                @endif
            </div>



                <hr>



                <modal ref="seguir" title="Nueva foto" :showok="false" :showcancel="false">
                    <div class="row col-12">
                        <clipper-upload v-model="imgURL" class="subir_foto col-3 text-center offset-1">Subir foto</clipper-upload>
                        <button @click="getResult" class="subir_foto col-3 offset-3">Cortar</button>
                        <button @click="mostrarOriginal" class="subir_foto col-3 offset-4 mt-2">Ver imagen</button>
                    </div>
                    <div class="col-12 text-center">
                        <clipper-basic v-if="hide_original" class="my-clipper" ref="clipper" :src="imgURL" ratio="1" class="col-12">
                            <div class="placeholder" slot="placeholder">Sin foto</div>
                        </clipper-basic>
                        <h3>Vista previa</h3>
                        <img class="result w-100" :src="resultURL" alt="">
                    </div>
                    <div class="col-12 text-center mt-3">
                        <textarea rows="2" cols="40" id="txtDescripcion" v-model="descripcion" placeholder="Descripción"></textarea>
                        <div v-html="error_nueva_foto"></div>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <button @click="GuardarNuevaFoto" class="col-6 btn btn-primary">Guardar</button>
                    </div>


                </modal>


                <modal ref="obtenercoins" title="Cobro" @ok="GuardarNuevaFoto">
                    <div style="padding: 10px">
                        <input type="number" v-model="monto" class="form-control" placeholder="Cantidad a comprar">

                        <cobro_compra_coins ref="cobro" :cobro="''+monto" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                                            :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                                            @terminado="terminado"></cobro_compra_coins>
                    </div>
                </modal>


                <modal ref="obtenersemanas" title="" :showok="false">
                    <div style="padding: 10px">

                        <div class="row text-center">
                            <img src="{{asset('images/2021/actualizar_plan.png')}}" class="col-8 offset-2">
                            <div class="card tarjeta_precios">
                                <div class="card-body">
                                    <table class="col-12">
                                        <tbody>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 1 semana</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 100 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 2 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 200 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 4 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 400 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 8 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 500 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 12 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 750 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 26 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 1500 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        <tr class="col-12">
                                            <td class="col-6 text-left">Quiero agregar 52 semanas</td>
                                            <td class="col-4 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 2500 Acton coins</td>
                                            <td class="col-2"><button class="col-12 btnagregar">Agregar</button></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <cobro_compra_coins ref="cobro" :cobro="''+monto" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                                            :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                                            @terminado="terminado"></cobro_compra_coins>

                    </div>
                </modal>



                <!--modal ref="nuevasfoto" title="Nueva foto">
                </modal-->

                <div class="modal fade" id="image-gallery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button @click="interacciones" type="button" class=" float-left flechas-left" id="show-previous-image"><img src="{{asset('images/2021/izquierda.png')}}" class="w-25">
                                </button>
                                <button @click="interacciones" type="button" id="show-next-image" class=" float-right flechas-right"><img src="{{asset('images/2021/derecha.png')}}"  class="w-25">
                                </button>
                                <img id="image-gallery-image" class="img-responsive col-md-12" src="" data-id="" data-img-id="">
                                <img id="" class="trespuntos" src="{{asset('images/2021/puntos.png')}}" data-id="" @click="mostrarmenumodal">
                                <div v-if="mostrarAcordion" class="accordion" id="accordionExample2" style="border: 1px solid white;">
                                    <div class="card" style="    border-bottom: 1px solid white;">
                                        <div class="card-header" id="uno">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link" type="button" @click="mostrarmini('privacidad')">
                                                    Privacidad
                                                </button>
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('conteo')">
                                                    Conteo de reacciones
                                                </button>
                                            </h5>
                                        </div>
                                    </div>



                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('administrar')">
                                                    Administrar comentarios
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('editar')">
                                                    Editar descripción
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"  @click="mostrarmini('eliminar')">
                                                    Eliminar foto/video
                                                </button>
                                            </h5>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal_mini">



                                    <div v-if="privacidad_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Esta publicación la pueden ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarPrivacidad('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarPrivacidad('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="conteo_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            El conteo de reacciones de esta publicacion lo puede ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarConteo('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarConteo('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="administrar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Los comentarios de esta publicacion lo puede ver
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarComentarios('1')">Todos</button>
                                            <button class="btn btn-success col-4 offset-1" @click="guardarComentarios('0')">Solo yo</button>
                                        </div>
                                    </div>

                                    <div v-if="eliminar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            ¿Estas seguro que deseas eliminar este archivo?
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="eliminarElemento()">Eliminar</button>
                                            <button class="btn btn-danger col-4 offset-1" @click="ocultarmini()">Cancelar</button>
                                        </div>
                                    </div>

                                    <div v-if="editar_mini" class="card" style="padding: 20px;">
                                        <div class="text-center col-12 mb-3">
                                            Editar descripción
                                        </div>
                                        <div class="text-center col-12">
                                            <textarea v-model="descripcion_edicion"></textarea>
                                        </div>
                                        <div class="text-center col-12">
                                            <button class="btn btn-success col-4 offset-1" @click="guardarDescripcion()">Guardar</button>
                                            <button class="btn btn-success col-4 offset-1" @click="ocultarmini()">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-12 row text-center mt-3 mb-3 ml-1">
                                    <div class="col-3">
                                        <img v-if="!dar_acton" src="{{asset('images/2021/dar_acton.png')}}" @click="darCoinsLike()" class="w-50">
                                        <img v-else src="{{asset('images/2021/dar_acton_color.png')}}" @click="darCoinsLike()" class="w-50">
                                    </div>
                                    <div class="col-3">
                                        <img v-if="!like_acton" src="{{asset('images/2021/like_acton.png')}}" @click="darLike('like')" class="w-50">
                                        <img v-else src="{{asset('images/2021/like_acton_color.png')}}" @click="darLike('like')" class="w-50">
                                    </div>
                                    <div class="col-3">
                                        <img  v-if="!flor_acton" src="{{asset('images/2021/flor_acton.png')}}" class="" @click="darLike('flor')" width="35%">
                                        <img v-else src="{{asset('images/2021/flor_acton_color.png')}}" @click="darLike('flor')" class="" width="35%">
                                    </div>
                                    <div class="col-3">
                                        <img  v-if="!fuego_acton" src="{{asset('images/2021/fuego_acton.png')}}" @click="darLike('fuego')" class="" width="35%">
                                        <img v-else src="{{asset('images/2021/fuego_acton_color.png')}}" @click="darLike('fuego')" class="" width="35%">
                                    </div>
                                </div>
                                <div v-if="darCoins" class="row col-12 text-center">
                                    <input type="number" id="txtactoncoins" min="1" class="darcoins col-8 offset-2" placeholder="Acton coins"><br>
                                    <button class="btn btn-success col-6 offset-3 mt-2  " @click="enviarCoins">Enviar</button>
                                </div>
                                <div id="descripcion" class="row col-10 offset-1 mb-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="modal_mini">
                    <div v-if="modal_reto" class="card modal_reto" style="padding: 20px;">
                        <i class="fas fa-times close-top" @click="ocultarmini"></i>
                        <div class="text-center col-12 mb-3 mt-4">
                            <h3 v-if="!reto_privado"><img src="{{asset('images/2021/monito.png')}}" class="mr-2" width="13%"> @{{reto_nombre}}</h3>
                            <h3 v-else><img src="{{asset('images/2021/monito.png')}}" class="mr-2" width="13%"> Privado</h3>
                            <div v-if="reto_respondido" class="text-center col-12 mt-3">
                                <!--video-- width="100%" controls>
                                    <source :src="reto_video" type="video/mp4">
                                </video-->
                                <video autoplay :src="'/cuenta/getVideo/'+ reto_id"  width="100%" controls>
                                    <source :src="'/cuenta/getVideo/'+ reto_id">
                                </video>
                            </div>
                            <div class="text-center col-12 mt-3">
                                <h3><img src="{{asset('images/2021/moneda_mini.png')}}" class="mr-2" width="13%"> @{{reto_coins}} Acton Coins</h3>
                            </div>
                            <div class="text-center col-12 mt-3">
                                <h4>El reto consiste en:</h4>
                                <h3>@{{reto_descripcion}}</h3>
                            </div>
                            <div v-if="!reto_respondido" class="col-12 countdown-reto">
                                <br>
                                <div class="col-12"><h6 class="text-center">Tu tiempo para realizar este reto:</h6></div>
                                <br>
                                <div v-if="!reto_respondido" class="row ">
                                    <div class="col-4 text-center">
                                        @{{ horas_reto }}<br>
                                        Horas
                                    </div>
                                    <div class="col-4 text-center">
                                        @{{ minutos_reto }}<br>
                                        Min.
                                    </div>
                                    <div class="col-4 text-center">
                                        @{{ segundos_reto }}<br>
                                        Seg.
                                    </div>
                                </div>
                            </div>
                            <div v-if="!mostrar_subida && horas_reto>0 && minutos_reto>0 && segundos_reto>0" class="text-center col-12 mt-4">
                                <img src="{{asset('images/2021/aceptar_reto.png')}}" @click="aceptar( reto_id )" class="" width="48%">
                                <img src="{{asset('images/2021/declinar_reto.png')}}" @click="declinar( reto_id )" class="" width="48%">
                            </div>
                            <div v-if="mostrarcalif" class="text-center col-12 mt-4 row">
                                <div class="col-6">
                                    <img @click="califica(reto_id, 'si')" src="{{asset('images/2021/gusta_reto.png')}}" class="w-75">
                                </div>
                                <div class="col-6">
                                    <img @click="califica(reto_id, 'no')" src="{{asset('images/2021/no_gusta_reto.png')}}" class="w-75">
                                </div>
                            </div>
                            <!--div v-if="mostrar_subida && horas_reto==0 && minutos_reto==0 && segundos_reto==0" class="text-center col-12 mt-4">
                                <h2>Ya no puedes aceptar este reto</h2>
                            </div-->
                            <div v-if="mostrar_subida && !reto_respondido && horas_reto > 0" class="text-center col-12 mt-4">
                                <div class="image-upload">
                                    <label for="file-input">
                                        <img src="{{asset('images/2021/subir_reto.png')}}" width="60%"/>
                                    </label>

                                    <input id="file-input" type="file" @change="videoDuracion"/>
                                    <label>@{{error_video}}</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div v-if="modal_coins" class="card modal_coins" style="padding: 20px;">
                        <i class="fas fa-times close-top" @click="ocultarmini"></i>
                        <div class="text-center">
                            <br>
                            <br>
                            <br>
                            <br>
                            <h3 class="mt-5">Obtuviste Acton Coins.</h3>
                            <div v-html="tipo_album"></div>
                            <h3><img src="{{asset('images/2021/moneda_mini.png')}}" class="mr-2 ml-2" width="6%"> @{{notificacion_coins}}</h3>
                            <h3>@{{ tipo_compra }}</h3>
                        </div>
                    </div>
                    <div v-if="modal_reacciones" class="card modal_reacciones" style="padding: 20px;">
                        <i class="fas fa-times close-top" @click="ocultarmini"></i>
                        <div class="text-center">
                            <br>
                            <br>
                            <br>
                            <br>

                            <div v-html="imagen_album"></div>
                        </div>
                    </div>
                </div>
            </div>
    </template>
@endsection

@section('scripts')
    @if(\Illuminate\Support\Facades\Auth::user()->vencido)
        <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
        <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
        <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
        <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    @endif
    <script>
        function trigger(id){
            var l = document.getElementById(''+id);
            for(var i=0; i<5; i++){
                l.click();
            }
        }
        $(document).ready(function ()
        {
            $('.NO-CACHE').attr('src',function () { return $(this).attr('src') + "?a=" + Math.random() });
        });
    </script>
    <script>

        Vue.component(VueQrcode.name, VueQrcode);

        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['usuario', 'referencias','monto','original','descuento','saldo', 'fotos', 'retos', 'seguidos', 'siguen'],
            data: function(){
                return{
                    nombre:'',
                    referenciados: [],
                    pagos: [],
                    filtros:{
                        referencia: '',
                        estado: '0',
                        ciudad: '0',
                        cp: '0',
                        colonia: '0',
                        tiendagym: '0',
                        conexion: '0',
                        ingresadosReto: '',
                        codigo_personal: '',
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: ''
                    },
                    buscando: false,
                    imgURL: '',
                    resultURL: '',
                    descripcion: '',
                    montopago: 0,
                    dias_hasta: 0,
                    horas: 0,
                    minutos: 0,
                    segundos: 0,
                    saldochk: false,
                    dias: 24,
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],
                    tiendas:[],
                    conexiones:[],
                    frmBusqueda: false,
                    usuariosSeguir: [],
                    compras:[],
                    reacciones:[],
                    imgURL: '',
                    resultURL: '',
                    dar_acton: false,
                    flor_acton: false,
                    fuego_acton: false,
                    like_acton: false,
                    darCoins: false,
                    mostrarAcordion: false,
                    eliminar_mini: false,
                    editar_mini: false,
                    administrar_mini: false,
                    privacidad_mini: false,
                    conteo_mini: false,
                    descripcion_edicion: '',
                    imagen_perfil: '{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id.'/'.rand(0,10))}}',
                    modal_reto: false,
                    modal_coins: false,
                    modal_reacciones: false,
                    notificacion_coins: 0,
                    reto_nombre: '',
                    reto_descripcion: '',
                    reto_coins: '',
                    reto_privado: '',
                    fecha_reto: '',
                    horas_reto: 0,
                    minutos_reto: 0,
                    segundos_reto: 0,
                    reto_id: 0,
                    reto_notificacion: '',
                    aceptado_reto: '',
                    error_video: '',
                    mostrar_subida: false,
                    reto_respondido: false,
                    reto_video: '',
                    tipo_album: '',
                    imagen_album: '',
                    hide_original: true,
                    mostrarcalif: false,
                    ocultareto: false,
                    error_nueva_foto: '',
                }},
            methods: {
                sigue: function () {
                    var vm = this;
                    this.$refs.seguir.showModal();
                    vm.imgURL = '';
                    vm.resultURL = '';
                    vm.error_nueva_foto = '';
                },
                obtenercoins: function () {
                    var vm = this;
                    this.$refs.obtenercoins.showModal();


                },
                obtenersemanas: function () {
                    var vm = this;
                    this.$refs.obtenersemanas.showModal();


                },
                upload: function(e){
                    if (e.target.files.length !== 0) {
                        if(this.imgURL) URL.revokeObjectURL(this.imgURL)
                        this.imgURL = window.URL.createObjectURL(e.target.files[0]);
                    }
                },
                getResult: function () {
                    var vm = this;
                    vm.hide_original = false;
                    const canvas = this.$refs.clipper.clip();//call component's clip method
                    this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image
                },
                mostrarOriginal: function () {
                    var vm = this;
                    vm.hide_original = true;
                },
                GuardarNuevaFoto: function() {
                    let vm = this;
                    let fm = new FormData();
                    fm.append('imagen', this.resultURL);
                    fm.append('descripcion', this.descripcion);
                    vm.error_nueva_foto = '';
                    if(this.descripcion != '' && this.imgURL != ''){
                        axios.post('{{url('cuenta/mialbum/nuevaFoto/')}}', fm).then(function (response) {
                            vm.loadingFoto = false;
                            window.location.reload();
                            if (response.data.status == 'ok'){
                            }
                        }).catch(function (error) {
                            console.log(error.response.data.errors);
                        });
                    }else{
                        if(this.descripcion == ''){
                            vm.error_nueva_foto = '<span class="text-danger">Llena la descripción.</span><br>';
                            $('.btn-success').prop('disabled', false);
                        }
                        if(this.imgURL == ''){
                            vm.error_nueva_foto += '<span class="text-danger">Selecciona la fotografia.</span>';
                            $('.btn-success').prop('disabled', false);
                        }
                    }
                },
                galeria: function(){
                    let current_image,
                        selector,
                        counter = 0;
                    var setIDs = true;
                    var setClickAttr = 'a.thumbnail';

                    function disableButtons(counter_max, counter_current) {
                        $('#show-previous-image, #show-next-image')
                            .show();
                        if (counter_max === counter_current) {
                            $('#show-next-image')
                                .hide();
                        } else if (counter_current === 1) {
                            $('#show-previous-image')
                                .hide();
                        }
                    }

                    $('#show-next-image, #show-previous-image')
                        .click(function () {
                            if ($(this)
                                .attr('id') === 'show-previous-image') {
                                current_image--;
                            } else {
                                current_image++;
                            }

                            selector = $('[data-image-id="' + current_image + '"]');
                            updateGallery(selector);
                        });

                    function updateGallery(selector) {
                        let $sel = selector;
                        current_image = $sel.data('image-id');
                        var ids = $sel.attr('data-img-id');
                        $('#image-gallery-title')
                            .text($sel.data('title'));
                        $('#image-gallery-image')
                            .attr('src', $sel.data('image'));
                        $('#image-gallery-image')
                            .attr('data-id', current_image);
                        $('#image-gallery-image')
                            .attr('data-img-id', ids);
                        $('#descripcion')
                            .html($sel.data('descripcion'));
                        disableButtons(counter, $sel.data('image-id'));
                    }

                    if (setIDs == true) {
                        $('[data-image-id]')
                            .each(function () {
                                counter++;
                                $(this)
                                    .attr('data-image-id', counter);
                            });
                    }
                    $(setClickAttr)
                        .on('click', function () {
                            updateGallery($(this));
                        });

                },
                darLike: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    axios.post('{{url('cuenta/mialbum/darlike/')}}', {'tipo': tipo, 'id': dataID}
                    ).then(function (response) {
                        vm.loadingFoto = false;
                        console.log(response.data);
                        if (response.data.status == 'agregado'){
                            if(tipo == 'fuego'){
                                vm.fuego_acton = true;
                            }
                            if(tipo == 'flor'){
                                vm.flor_acton = true;
                            }
                            if(tipo == 'like'){
                                vm.like_acton = true;
                            }
                        }else{
                            if(tipo == 'fuego'){
                                vm.fuego_acton = false;
                            }
                            if(tipo == 'flor'){
                                vm.flor_acton = false;
                            }
                            if(tipo == 'like'){
                                vm.like_acton = false;
                            }
                        }
                    });
                },
                interacciones: function(){
                    var vm = this;
                    setTimeout(function(){
                        vm.fuego_acton = false;
                        vm.flor_acton = false;
                        vm.like_acton = false;
                        vm.dar_acton = false;
                        var element = document.getElementById('image-gallery-image');
                        var dataID = element.getAttribute('data-img-id');
                        axios.post('{{url('cuenta/mialbum/reacciones/')}}', {'id': dataID}
                        ).then(function (response) {
                            vm.loadingFoto = false;
                            //console.log(response.data);
                            Object.keys(response.data).forEach(function(key) {
                                console.log(key, response.data[key].tipo)
                                if(response.data[key].tipo_like == 'fuego'){
                                    vm.fuego_acton = true;
                                }
                                if(response.data[key].tipo_like == 'flor'){
                                    vm.flor_acton = true;
                                }
                                if(response.data[key].tipo_like == 'like'){
                                    vm.like_acton = true;
                                }
                                if(response.data[key].tipo_like == 'coins'){
                                    vm.dar_acton = true;
                                }
                            })
                        });
                    }, 500);
                },
                guardarPrivacidad: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'privacidad'}
                    ).then(function (response) {
                    });
                    vm.privacidad_mini = false;

                },
                guardarComentarios: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    vm.administrar_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'comentarios'}
                    ).then(function (response) {
                    });

                },
                eliminarElemento: function(){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    vm.administrar_mini = false;
                    axios.post('{{url('cuenta/eliminarElemento/')}}', {'id': dataID}
                    ).then(function (response) {
                        window.location.reload();
                    });

                },
                guardarDescripcion: function(){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    vm.editar_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': vm.descripcion_edicion, 'tipo': 'descripcion'}
                    ).then(function (response) {
                        $('#descripcion')
                            .html(vm.descripcion_edicion);
                        $('#'+dataID).attr('data-descripcion', vm.descripcion_edicion);
                    });

                },
                guardarConteo: function(tipo){
                    var vm = this;
                    var element = document.getElementById('image-gallery-image');
                    var dataID = element.getAttribute('data-img-id');
                    vm.conteo_mini = false;
                    axios.post('{{url('cuenta/guardaPublico/')}}', {'id': dataID, 'publico': tipo, 'tipo': 'conteo'}
                    ).then(function (response) {
                    });

                },
                countdown: function(){
                    var that = this;
                    setInterval(function() {
                        var diaInicioReto = that.usuario.inicio_reto;
                        var mdy = diaInicioReto.split('-');
                        var dia_inicio = new Date(mdy[0], mdy[1]-1, mdy[2]);
                        var todayDate = new Date();
                        var distance = dia_inicio - todayDate;
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        that.dias_hasta = days;
                        that.horas = hours;
                        that.minutos = minutes;
                        that.segundos = seconds;
                    }, 1000);
                },
                countdownReto: function(){
                    var that = this;
                    const today = new Date(that.fecha_reto)
                    const tomorrow = new Date(today)
                    tomorrow.setDate(tomorrow.getDate() + 1)
                    setInterval(function() {
                        var now = new Date().getTime();
                        var distance = tomorrow - now;
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        that.horas_reto = ("00" + hours).slice(-2);
                        that.minutos_reto = ("00" + minutes).slice(-2);
                        that.segundos_reto = ("00" + seconds).slice(-2);
                        if (distance <= 0){
                            that.horas_reto = 0;
                            that.minutos_reto = 0;
                            that.segundos_reto = 0;
                        }
                    }, 1000);
                },
                darCoinsLike: function(){
                    this.darCoins = true;
                },
                enviarCoins: function(){
                    var vm = this;
                    this.darCoins = true;
                    var element = document.getElementById('image-gallery-image');
                    var coins = document.getElementById('txtactoncoins').value;
                    var dataID = element.getAttribute('data-img-id');
                    alert(dataID);
                    axios.post('{{url('cuenta/mialbum/enviarcoins/')}}', {'id': dataID, 'coins': coins}
                    ).then(function (response) {
                        if (response.data.status == 'agregado'){
                            vm.dar_acton = true;
                            vm.darCoins = false;
                        }else{
                            alert('No cuentas con suficiente acton coins');
                            vm.dar_acton = false;
                            vm.darCoins = true;
                        }

                    });
                },
                terminado: function () {
                    window.location.href = 'home';
                },
                mostrarmenumodal: function(){
                    var vm = this;
                    if(vm.mostrarAcordion){
                        vm.mostrarAcordion = false;
                    }else {
                        vm.mostrarAcordion = true;
                    }
                },
                ocultarmini: function(){
                    var vm = this;
                    vm.modal_reto = false;
                    vm.modal_coins = false;
                    vm.modal_reacciones = false;
                },
                mostrarmini: function(nombre, coins, descripcion, fecha, publico, tipo, id, id_reto, aceptado, update, video){
                    var vm = this;
                    console.log(video);
                    vm.modal_reto = true;
                    vm.reto_nombre = nombre;
                    vm.reto_descripcion = descripcion;
                    vm.reto_coins = coins;
                    vm.reto_publico = publico;
                    vm.fecha_reto = fecha;
                    vm.reto_id = id_reto;
                    vm.reto_notificacion = id;
                    vm.aceptado_reto = aceptado;
                    vm.update = update;
                    if(video !== ''){
                        vm.reto_respondido = true;
                    }else{
                        vm.reto_respondido = false;
                    }
                    vm.reto_video = video;
                    if(aceptado){
                        vm.fecha_reto = update;
                        vm.mostrar_subida = true;
                    }
                    vm.countdownReto();
                    vm.verificaCalificacion(id_reto);
                },
                verificaCalificacion: function(id_reto){
                    var vm = this;
                    axios.post('{{url('cuenta/retos/verificacalificacion/')}}', {'id': id_reto}
                    ).then(function (response) {
                        if (response.data.status == 'no'){
                            vm.mostrarcalif = true;
                        }else{
                            vm.mostrarcalif = false;
                        }
                    });
                },
                califica: function(id_reto, califica){
                    var vm = this;
                    axios.post('{{url('cuenta/retos/calificacion/')}}', {'id': id_reto, 'cal': califica}
                    ).then(function (response) {
                        vm.mostrarcalif = false;
                    });
                },
                mostrarminicoins: function(monto, tipo, imagen='', referencia=''){
                    var vm = this;
                    vm.modal_coins = true;
                    vm.notificacion_coins = monto;
                    vm.tipo_album = '';
                    vm.tipo_compra = '';
                    if(tipo == 'oxxo' || tipo == 'spei' || tipo == 'tarjeta'){
                        vm.tipo_compra = tipo;
                    }
                    if(tipo == 'reto'){
                        vm.tipo_compra = 'Por el reto: '+referencia;
                    }
                    if(tipo=='album'){
                        vm.tipo_album = '<div class="col-12 text-center"><img src="'+imagen+'" width="100%"></div>';
                    }
                },
                mostrarminireacciones: function(nombre, imagen, tipo){
                    var vm = this;
                    vm.modal_reacciones = true;
                    vm.tipo_album = '';
                    if (tipo == 'like'){
                        tipo = '/images/2021/like_acton.png'
                    }
                    if (tipo == 'coins'){
                        tipo = '/images/2021/coins_acton.png'
                    }
                    if (tipo == 'flor'){
                        tipo = '/images/2021/flor_acton.png'
                    }
                    vm.imagen_album = '<div class="col-12 text-center"><img src="'+imagen+'" width="100%"></div><br><br>' +
                            '<img src="/images/2021/monito.png" class="mr-2" width="13%"> '+nombre+' Reacciono a tu foto'+
                        '<div class="col-12 text-center"><img src="'+tipo+'" width="20%"></div>';
                },
                aceptar: function(id){
                    var vm = this;
                    axios.post('{{url('cuenta/aceptarreto/')}}', {'id': id, 'tipo': 'aceptar', 'id_notificacion': vm.reto_notificacion}
                    ).then(function (response) {
                        vm.mostrar_subida =true;
                        vm.reto_respondido =false;
                        vm.horas_reto = 24;
                    });
                },
                declinar: function(id){
                    var vm = this;
                    axios.post('{{url('cuenta/aceptarreto/')}}', {'id': id, 'tipo': 'declinar', 'id_notificacion': vm.reto_notificacion}
                    ).then(function (response) {
                        vm.mostrar_subida = true;
                    });
                },
                videoDuracion: function(){
                    var vm = this;
                    var vid = document.createElement('video');
                    var video = document.querySelector('#file-input');
                    var fileURL = URL.createObjectURL(video.files[0]);;
                    vid.src = fileURL;
                    // wait for duration to change from NaN to the actual duration
                    vid.ondurationchange = function() {
                        if (this.duration>20) {
                            vm.error_video = 'El video no puede durar mas de 20 segundos.';
                            $('#file-input').empty();
                        }else{
                            let fm = new FormData();
                            fm.append('video', video.files[0]);
                            fm.append('id', vm.reto_id);
                            if(this.descripcion != '' && this.imgURL != ''){
                                axios.post('<?php echo e(url('cuenta/retos/respuesta/')); ?>', fm).then(function (response) {
                                    if (response.data.status == 'ok'){
                                        vm.reto_respondido = true;
                                        vm.reto_video = response.data.video;
                                    }
                                }).catch(function (error) {
                                    console.log(error.response.data.errors);
                                });
                            }
                        }
                    };
                },
                diasChange: function (d) {
                    this.dias = d;
                    if(this.saldo >= 0) {
                        if (this.dias == 14) {
                            if (this.saldochk) {
                                if (this.saldo > 500) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 500 - this.saldo
                                }
                            } else {
                                this.montopago = 500
                            }
                        }
                        if (this.dias == 28) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 1000 - this.saldo
                                }
                            } else {
                                this.montopago = 1000
                            }
                        }
                        if (this.dias == 56) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 2000 - this.saldo
                                }
                            } else {
                                this.montopago = 2000
                            }
                        }
                        if (this.dias == 84) {
                            if (this.saldochk) {
                                if (this.saldo > 3000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 3000 - this.saldo
                                }
                            } else {
                                this.montopago = 3000
                            }
                        }
                        if (this.montopago == 0) {
                            $("#pagarceros").show();
                        } else {
                            $("#pagarceros").hide();

                        }
                    }
                    this.saveDiasNuevo();
                },
                saveDiasNuevo: function(){
                    if (this.saldochk) {
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias+'001', {saldo: this.saldo, operacion: 'resta', usa: 1}).then(function (response) {
                        });
                    }else{
                        axios.get('{{url('/usuarios/actualizar_dias/')}}/' + this.dias+'000', {saldo: this.saldo, operacion: 'suma', usa: 0}).then(function (response) {
                        });
                    }
                },
                pagaRefrendo: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/refrendar_ceros')}}', {dias: this.dias}).then((response) => {
                        location.reload();
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                check: function(e) {
                    if(this.saldo > 0) {
                        console.log(this.saldochk)
                        if (this.dias == 14) {
                            if (this.saldochk) {
                                if (this.saldo > 500) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 500 - this.saldo
                                }
                            } else {
                                this.montopago = 500
                            }
                        }
                        if (this.dias == 28) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 1000 - this.saldo
                                }
                            } else {
                                this.montopago = 1000
                            }
                        }
                        if (this.dias == 56) {
                            if (this.saldochk) {
                                if (this.saldo > 1000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 2000 - this.saldo
                                }
                            } else {
                                this.montopago = 2000
                            }
                        }
                        if (this.dias == 84) {
                            if (this.saldochk) {
                                if (this.saldo > 3000) {
                                    this.montopago = 0
                                } else {
                                    this.montopago = 3000 - this.saldo
                                }
                            } else {
                                this.montopago = 3000
                            }
                        }
                        if (this.montopago == 0) {
                            $("#pagarceros").show();
                        } else {
                            $("#pagarceros").hide();

                        }
                    }
                    this.saveDiasNuevo();
                },
            },
            mounted: function () {
                $("#pagarceros").hide();
                this.countdown();
                //this.diasChange(14);
                /*this.filtros.referencia = this.usuario.referencia;
                this.getEstados();
                this.getTiendas();
                this.buscar();
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                if(params.get("_token")) {
                    this.buscar();
                }*/
                this.galeria();
                @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                /*this.$refs.cobro.configurar(
                this.usuario.name,
                this.usuario.last_name,
                this.usuario.email,
                this.usuario.telefono,
                this.usuario.codigo
            );*/
                @endif

            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
