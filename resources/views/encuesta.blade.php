@extends('layouts.app_encuesta')
@section('header')
    <style>
        .encuesta-leave-active { /*animacion para espiral activo con false */
            position: absolute;
            z-index: -1;
            /*animation: salida 1.3s;*/
        }

        @keyframes salida {
            0% {
                transform: translateY(0%);
            }
            50% {
                transform: translateY(-100%);
            }
            70% {
                transform: translateY(-80%);
            }
            100% {
                transform: translateY(-400%);
            }
        }

        .encuesta-enter-active { /*animacion para spiral activo cuando es true*/
            animation: entrada 1s;
        }

        @keyframes entrada {
            0% {
                transform: translateX(400%);
            }
            100% {
                transform: translateX(0%);
            }
        }


        @-webkit-keyframes spiral {
            from {
                stroke-dashoffset: 588;
            }
            to {
                stroke-dashoffset: 0;
            }
        }

        .vertical-enter-active { /*Animacion para rayado horizontal*/
            stroke-dasharray: 1009.6, 1009.6;
            -webkit-animation: vertical 0.5s linear;
        }

        @-webkit-keyframes vertical {
            from {
                stroke-dashoffset: 1000;
            }
            to {
                stroke-dashoffset: 0;
            }
        }

        svg {
            border: 3px solid #ffa321;
            border: 3px solid #0080DD;
            margin: 5px;
            width: 40px;
        }

        svg.spiral {
            border-radius: 50%;
        }

        svg.vertical {
            border-radius: 0%;
        }

        .pregunta label{
            background: #F2F2F2;
            padding: 3px;
            width: 80%;
            font-size: 15px;
        }

        .respuesta svg, .respuesta label {
            float: left;
            cursor: pointer;
        }

        .respuesta label {
            margin: 8px;
        }

        .siguiente {
            color: red;
            background: none;
            border: none;
        }

        .tarjeta input {
            margin: 5px;
        }

        label.cuestionario {
            font-size: 0.5rem;
        }

        input.form-control {
            margin: 10px 0;
        }

        input.form-control.encuesta {
            padding-bottom: 15px;
            padding-top: 15px;
            font-size: 10pt;
        }

        .siguiente, .anterior {
            color: #1b4b72;
            background: none;
            border: none;
            font-size: 1.5em;
            display: block;
            margin: 10px auto;
        }

        .pregunta {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .respuesta{
            font-size: .8rem;
        }

        a.btn-primary {
            font-size: 18pt;
            background-color: #f90;
            border-color: #f90;
            padding: 2% 20%;
        }

        a.btn-primary:hover {
            background-color: #f90;
        }

        .card-body {
            background-color: #f6f6f6;
            background-color: white;
            /*background-image: url("{{asset('/img/rayogris.png')}}");*/
            background-repeat: no-repeat;
            background-position: center;
        }

        .card-header{
            background: white;
            color: #666666;
        }
        .navbar-laravel {
            background-color: transparent !important;
        }

        .card-header-pregunta{
            background: #0080DD;
            color: white;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            text-transform: uppercase;
            font-weight: bolder;
        }

        .card{
            border: 0px;
        }
        .btn-primary {
            font-size: 1em;
            background-color: #ff9900;
            border: 1px solid #ff9900;
            padding: 0.5em 2em;
            text-transform: uppercase;
            font-weight: bold;
        }

        .Hombre{
            border: 2px solid #0080DD !important;
        }



        .Mujer{
            border: 2px solid #B400B9 !important;
        }


        .Hombretext { /* Microsoft Edge */
            color: #0080DD !important;
        }

        .Mujertext { /* Microsoft Edge */
            color: #B400B9 !important;
        }


        .Hombretext2 { /* Microsoft Edge */
            color: #0080DD !important;
            font-family: "Nunito", sans-serif;
            font-weight: bold;
        }

        .Mujertext2 { /* Microsoft Edge */
            color: #B400B9 !important;
            font-family: "Nunito", sans-serif;
            font-weight: bold;
        }


        .Hombresvg svg {
            border: 3px solid #0080DD;
            fill: #0080DD !important;
        }


        .Mujersvg svg {
            border: 3px solid #B400B9 !important;
            fill: #B400B9 !important;
        }

        .Mujer_header{
            background:#B400B9 !important;
        }

        .Hombre_header{
            background:#0080DD !important;
        }

        .ayuda_pregunta{
            color: #0080DD;
            background: transparent !important;
            margin-top: -40px;
            margin-bottom: 40px;
            font-family: Arial;
            font-weight: lighter;
            color: #0080DD;
            background: transparent !important;
            margin-top: -40px;
            margin-bottom: 10px;
            font-family: Arial;
            font-weight: lighter;
        }
        .card, .card-body{
            background: transparent !important;

        }
        .image-upload-1>input, .image-upload-2>input {
            display: none;
        }
        label.peso_ideal {
            font-size: 80px;
            font-weight: 700;
            color: #0080DD;
        }

        #imgfoto2 img {
           width: 100px;
           position: absolute;
           margin-top: -44%;
           margin-left: -47%;
            height: 93px;
       }
        #imgfoto1 img {
            width: 98px;
            margin-top: -46%;
            margin-left: -46%;
            z-index: 999999999999999999999;
            position: absolute;
            height: 93px;
        }
        .card-header-pregunta-singenero{
            background: transparent !important;
            padding: 0px !important;
            margin-bottom: -63px !important;
            border-bottom: 0px solid rgba(0, 0, 0, 0.125) !important;
            margin-top: 55px !important;
        }
        #app {
            background-image: url("{{asset('images/2021/fondo_rayo.png')}}") !important;
            background-size: 100% !important;
            background-attachment: fixed !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
            background-size: cover !important;
            max-height: 100%;
        }

        @media only screen and (max-width: 420px) {
            .card-body{
                padding: 2px;
                padding: 2px;
                max-height: 410px;
                min-height: 410px;

            }

            label.cuestionario {
                font-size: 0.9rem;
            }

            svg{
                width: 20px;
                margin-left: 20px;
            }
            .card, .card-body {
                background: transparent !important;
                width: 100%;
                margin-left: 0%;
                margin-top: -2%;
                margin-bottom: 40px;
                overflow-y: auto;
                overflow-x: hidden;
            }
        }
        .cuenta_circulo{
            position: absolute;
            left: 0;
            bottom: 0;
        }
        .justify-content-between{
            position: absolute;
            /* top: 0; */
            bottom: 0;
            margin-left: 40%;
            margin-bottom: 5px;
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <encuesta :p_preguntas="{{$preguntas}}" :urls="{{$urls}}" :p_user="{{$user}}"></encuesta>
        </div>
    </div>
    <template id="encuesta-template">
        <div v-if="!user.validado && !user.encuestado">
            <div class="card">

                <div v-else-if="!terminar && !continuar && pregunta!=='Sexo'">
                    <br><br>
                </div>
                <div class="card-header" v-if="inicio.mostrar && !terminar" style="background: transparent !important;">
                    <div class="d-flex flex-wrap" style="padding: 20px">
                        <div class="col-12 col-sm-6 text-center" style="border-right: 1px solid #fff">
                            <img src="{{asset('images/2021/mensaje_inicial_encuesta.png')}}" class="w-100">
                        </div>
                    </div>
                </div>
                <div v-else-if="!terminar && !continuar && pregunta!=='Sexo'" class="card-header text-center card-header-pregunta" :class="sexoheader" style="padding: 20px; font-size: 1.2rem;">
                    <span v-if="cuenta_circulo==2">
                        PESO Y ESTATURA
                    </span>
                    <span v-else>
                        @{{ pregunta }}
                    </span>

                </div>
                <div v-if="!terminar && pregunta=='Sexo'" class="card-header text-center card-header-pregunta-singenero" style="padding: 20px; font-size: 1.2rem;">
                    <h3 style="font-family: 'Nunito';font-weight: bolder;">SOY</h3>
                </div>
                <div class="card-body" :style="inicio.mostrar?'padding:0':''">
                    <div v-if="inicio.mostrar">
                        <img src="{{asset('images/2021/blanco_negro_encuesta.png')}}" width="100%">
                    </div>
                    <br>
                    <br>
                    <div class="col-12 col-sm-6 text-center" v-if="inicio.mostrar">
                        <button class="btn btn-primary text-uppercase font-weight-bold"
                                style="margin-top: 20px; padding: 10px 80px;"
                                @click="mostrarCerradasUno()">Comenzar
                        </button>
                    </div>

                    <transition :name="mostrarEncuesta.animacion">
                        <div v-if="mostrarEncuesta.mostrar" class="col-sm-8 d-block mr-auto ml-auto">
                            <div v-for="(pregunta,key,index) in preguntasAbiertas">
                                <div v-if="(key < 2 || key == 3) && key !== 0">
                                    <div class="form-group row">
                                        <label v-if="key == 3" for="staticEmail" class="col-6 col-form-label" :class="sexotext2">@{{ pregunta.pregunta }}</label>
                                        <label v-else for="staticEmail" class="col-4 col-form-label" :class="sexotext2">@{{ pregunta.pregunta }}</label>
                                        <div class="col-6">
                                            <input class="form-control encuesta" :class="sexo" v-model="pregunta.respuesta"
                                               :placeholder="pregunta.pregunta">
                                            <form-error align="left" :name="pregunta.pregunta+'.respuesta'"
                                                :errors="errors" class="col-12 text-center"></form-error>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="key == 0">
                                    <div class="form-group row">
                                        <label for="staticEmail" class="col-4 col-form-label" :class="sexotext2">@{{ pregunta.pregunta }}</label>
                                        <div class="col-4">
                                            <input class="form-control encuesta" :class="sexo" v-model="pregunta.respuesta"
                                                   placeholder="Metros" :name="pregunta.respuesta">
                                            <form-error align="left" :name="pregunta.pregunta+'.respuesta'"
                                                        :errors="errors" class="col-12 text-center"></form-error>
                                        </div>
                                        <div class="col-4">
                                            <input class="form-control encuesta" :class="sexo" v-model="pregunta.respuesta"
                                                   placeholder="Centimetros" :class="sexotext2">
                                            <form-error align="left" :name="pregunta.pregunta+'.respuesta'"
                                                        :errors="errors" class="col-12 text-center"></form-error>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-top: 125px;">
                                <button class="siguiente" @click="comprobarAbiertas()">
                                    <i class="far fa-chevron-double-right"></i>
                                </button>
                            </div>
                            <form-error name="siguiente" :errors="errors"></form-error>
                        </div>
                    </transition>
                    <div v-if="!mostrar_abiertas" class="flex-row" v-for="(pregunta, indexP) in preguntasCerradas">
                        <transition name="encuesta"
                                    v-if="pregunta.multiple != undefined"> {{--animacion de la pantalla de css--}}
                            <div v-if="pregunta.mostrar">
                                <div class="d-block mr-auto ml-auto">
                                    <div class="form-group">
                                        <div class="row pregunta"> {{--Preguntas con opciones--}}
                                            <label class="ayuda_pregunta text-center col-12 col-sm-12" :class="sexotext" v-html="pregunta.ayuda"></label>

                                            <div v-if="indexP>0" class="col-12 col-sm-12" :class="sexosvg" v-for="(opcion, indexR) in pregunta.opciones">
                                                <input :id="pregunta.id+''+indexR" type="checkbox" v-show="false"
                                                       v-model="opcion.selected"
                                                       @change="seleccionar(pregunta, opcion)">
                                                <svg v-if="pregunta.multiple == 0" class="spiral" viewBox="0 0 100 100"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     @click="select(pregunta, opcion)">
                                                        <circle v-if="opcion.selected"  cx="50" cy="50" r="40"  />
                                                </svg>

                                                <svg v-else class="vertical" viewBox="0 0 100 100"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     @click="select(pregunta, opcion)">
                                                    <rect v-if="opcion.selected" x="10" y="10"  width="80" height="80"  />
                                                </svg>
                                                <label class="cuestionario"
                                                       :for="pregunta.id+''+indexR"> {{--texto pregunta--}}
                                                    @{{ opcion.respuesta }}
                                                </label>
                                            </div>

                                            <div v-if="indexP==0" class="col-6 col-sm-6" v-for="(opcion, indexR) in pregunta.opciones">
                                                <input :id="pregunta.id+''+indexR" type="checkbox" v-show="false"
                                                       v-model="opcion.selected"
                                                       @change="seleccionar(pregunta, opcion)">
                                                <div class="row" style="margin-top: 50px">
                                                    <img v-if="indexP==0 && indexR==0" src="{{asset('images/2021/mujer.png')}}" @click="select(pregunta, opcion)" class="w-100 col-12" :class="">
                                                    <img v-if="indexP==0 && indexR==1" src="{{asset('images/2021/hombre.png')}}" @click="select(pregunta, opcion)" class="w-100 col-12" :class="">
                                                </div>
                                            </div>

                                            <form-error name="seleccion" :errors="errors" class="col-12 text-center"></form-error>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between" style="">
                                        <button v-if="!terminar && indexP>0" class="anterior"
                                                @click="comprobarCerrada(pregunta, 0)">
                                            <i class="far fa-chevron-double-left"></i>
                                        </button>
                                        <button v-if="!terminar && indexP>0" class="siguiente"
                                                @click="comprobarCerrada(pregunta, 1)">
                                            <i class="far fa-chevron-double-right"></i>
                                        </button>
                                        <!--button v-else-if="!terminar && indexP==0" class="siguiente"
                                                @click="mostrarAbiertas()">
                                            <i class="far fa-chevron-double-right"></i>
                                        </button-->
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <transition name="encuesta">
                        <div v-if="terminar" align="center" class="text-center">
                            <div :class="sexotext">POR FAVOR RESPONDE LO SIGUIENTE</div>
                            <div v-for="(pregunta,key,index) in preguntasAbiertas" style="padding:10px;">
                                <textarea v-if="key == 2 || key == 4 || key == 5 || key == 6 || key == 7 || key == 8" class="form-control encuesta" v-model="pregunta.respuesta"
                                          :placeholder="pregunta.pregunta" rows="5" :class="sexo"></textarea>
                            </div>
                            <div v-if="!espera" style="display: flex; justify-content: space-between">
                                <button class="siguiente" @click="termina">
                                    <i class="far fa-chevron-double-right"></i>
                                </button>
                            </div>
                            <div v-if="espera" class="col-12 text-center">
                                <img src="{{asset('images/2021/loading_lan.gif')}}" class="col-2">
                                <img src="{{asset('images/2021/espera.png')}}" class="col-6">
                            </div>
                            <span v-if="errorAbierta" style="color:red;">Completa la información</span>
                            <form-error name="siguiente" :errors="errors"></form-error>
                        </div>
                        <div v-if="continuar" align="center" class="text-center">
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                            <!--a class="btn btn-primary btn-md" href="{{url('/reto/programa')}}"-->
                                <!--a class="btn btn-primary btn-md" href="{{url('/cuenta')}}">
                                    <span>Comenzar</span>
                                </a-->

                                <!--img src="{{asset('images/2021/primeros_30.png')}}" class="w-100 col-12" style="margin-top: 40px;">
                                <label class="peso_ideal">@{{ peso_ideal }} kg</label>
                                <img src="{{asset('images/2021/apartir_ahora.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;"-->

                                <img src="{{asset('images/2021/primeros_30.png')}}" class="w-100 col-12" style="margin-top: 40px;">
                                <label class="peso_ideal text-center">@{{ peso_ideal }} kg</label>
                                <img src="{{asset('images/2021/apartir_ahora.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                                <img src="{{asset('images/2021/top_seleccion.png')}}" class="w-100 col-12" style="margin-top: -30px; margin-bottom: 30px;">
                                <img v-if="user.genero == 1" src="{{asset('images/2021/credencial_mujer_1.png')}}" class="w-100 col-12" style="margin-top: -20px; margin-bottom: 30px;">
                                <img v-if="user.genero == 0" src="{{asset('images/2021/seleccion_archivo_1.png')}}" class="w-100 col-12" style="margin-top: -20px; margin-bottom: 30px;">
                                <div class="image-upload-1">
                                    <label for="imagen1">
                                        <img v-if="user.genero == 1" src="{{asset('images/2021/archivo_mujer.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                                        <img v-if="user.genero == 0" src="{{asset('images/2021/boton_archivo.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                                    </label>

                                    <input id="imagen1" type="file"  v-model="archivo1" ref="archivo1" @change="handleFileUpload1()" name="ok2"/>
                                </div>
                                <div id="imgfoto1">
                                    <img v-if="user.archivo_validacion_1" :src="user.archivo_validacion_1">
                                </div>
                                <div v-if="ok_imagen_1" class="text-danger text-center"><i class="fas fa-check-circle"></i> Imagen cargada correctamente.</div>
                                <div v-if="error_imagen_1" class="text-danger text-center col-12"><i class="fas fa-times-circle"></i> Verifica el peso de la imagen.</div>

                                <img v-if="user.genero == 1" src="{{asset('images/2021/selfie_mujer.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                                <img v-if="user.genero == 0" src="{{asset('images/2021/seleccion_archivo_2.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                                <div class="image-upload-2">
                                    <label for="imagen2">
                                        <img v-if="user.genero == 1" src="{{asset('images/2021/archivo_mujer.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                                        <img v-if="user.genero == 0" src="{{asset('images/2021/boton_archivo.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                                    </label>

                                    <input id="imagen2" type="file" v-model="archivo2" ref="archivo2" @change="handleFileUpload2()" name="ok"/>
                                </div>
                                <div id="imgfoto2">
                                    <img v-if="user.archivo_validacion_2" :src="user.archivo_validacion_2">
                                </div>
                                <div v-if="ok_imagen_2" class="text-danger text-center"><i class="fas fa-check-circle"></i> Imagen cargada correctamente.</div>
                                <div v-if="error_imagen_2" class="text-danger text-center"><i class="fas fa-times-circle"></i> Verifica el peso de la imagen.</div>
                                <div v-if="user.archivo_validacion_1 && user.archivo_validacion_2">
                                    <img src="{{asset('images/2021/subir_archivos.png')}}" class="w-100 col-8" @click="enviarValidacion()">
                                </div>

                        @else
                            <!--a class="btn btn-primary btn-md" href="{{url('/reto/dia/1/0/0')}}"-->
                                <!--a class="btn btn-primary btn-md" href="{{url('/cuenta')}}">
                                    <span>Comenzar</span>
                                </a-->
                        @endif
                        </div>
                    </transition>
                </div>

                <div v-if="cuenta_circulo>0 && cuenta_circulo<17" class="col-12 text-center cuenta_cirulo" >
                    <span v-for="index in 16" :key="index">
                        <!--img v-if="cuenta_circulo==index" src="{{asset('images/2021/punto_azul.png')}}" style="width: 10px; margin-left: 5px;"-->
                        <img v-if="cuenta_circulo==index" src="{{asset('images/2021/circulo_relleno_g.png')}}" style="width: 10px;">
                        <img v-else src="{{asset('images/2021/circulo_gris.png')}}" style="width: 10px; margin-left: 5px">
                        <!--img v-else src="{{asset('images/2021/punto.png')}}" style="width: 10px; margin-left: 5px;"-->
                    </span>
                </div>
            </div>

        </div>
        <div v-else>
            <div v-if="user.enviado_validacion==2" class="">
                <img src="{{asset('images/2021/felicidades.png')}}" class="" style="position: absolute; width: 100%; margin-left: -5px">
                <img src="{{asset('images/2021/bienvenido.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                <br>
                <p class="col-12 text-center" style="z-index: 9999; color: white;">Hola, @{{ user.nome }} @{{ user.last_name }}<br>
                Ya se te ha asignado un coach personal<br>
                    quién se comunicará contigo en las próximas horas<br>
                    y te dará indicaciones  par que empieces<br>
                    tu reto de la mejor manera<br>
                </p>
                <p class="col-12 text-center" style="z-index: 9999; color: white;">
                    Tu <strong>Coach Acton</strong> de ahora en adelante estará en<br>
                    comunicación contigo para que se resuelvan todas <br>
                    tus dudas y saques el máximo provecho de este<br>
                    programa.
                </p>
            <a class="text-center" href="{{url('/cuenta')}}" style="z-index: 9999; color: white; position: absolute">
                <img src="{{asset('images/2021/ingresar.png')}}" class="" style="margin-top: 20px; margin-bottom: 30px; width: 70%">

                                </a>
                <br>
                <br>
                <br>
            </div>
            <div v-else class="text-center">
                <div v-if="user.enviado_validacion==1" class="text-center">
                    <br>
                    <br>
                    <br>
                    <br>
                    <img src="{{asset('images/2021/espera_enviado_1.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">

                </div>
                <div v-if="user.enviado_validacion==0" class="text-center">
                    <img src="{{asset('images/2021/primeros_30.png')}}" class="w-100 col-12" style="margin-top: 40px;">
                    <label class="peso_ideal text-center">@{{ peso_ideal }} kg</label>
                    <img src="{{asset('images/2021/apartir_ahora.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                    <img src="{{asset('images/2021/top_seleccion.png')}}" class="w-100 col-12" style="margin-top: -30px; margin-bottom: 30px;">
                    <img v-if="user.genero == 1" src="{{asset('images/2021/credencial_mujer_1.png')}}" class="w-100 col-12" style="margin-top: -20px; margin-bottom: 30px;">
                    <img v-if="user.genero == 0" src="{{asset('images/2021/seleccion_archivo_1.png')}}" class="w-100 col-12" style="margin-top: -20px; margin-bottom: 30px;">
                    <div class="image-upload-1">
                        <label for="imagen1">
                            <img v-if="user.genero == 1" src="{{asset('images/2021/archivo_mujer.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                            <img v-if="user.genero == 0" src="{{asset('images/2021/boton_archivo.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                        </label>

                        <input id="imagen1" type="file"  v-model="archivo1" ref="archivo1" @change="handleFileUpload1()" name="ok2"/>
                    </div>
                    <div id="imgfoto1">
                        <img v-if="user.archivo_validacion_1" :src="user.archivo_validacion_1">
                    </div>
                    <div v-if="ok_imagen_1" class="text-danger text-center"><i class="fas fa-check-circle"></i> Imagen cargada correctamente.</div>
                    <div v-if="error_imagen_1" class="text-danger text-center"><i class="fas fa-times-circle"></i> Verifica el peso de la imagen.</div>

                    <img v-if="user.genero == 1" src="{{asset('images/2021/selfie_mujer.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                    <img v-if="user.genero == 0" src="{{asset('images/2021/seleccion_archivo_2.png')}}" class="w-100 col-12" style="margin-top: 20px; margin-bottom: 30px;">
                    <div class="image-upload-2">
                        <label for="imagen2">
                            <img v-if="user.genero == 1" src="{{asset('images/2021/archivo_mujer.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                            <img v-if="user.genero == 0" src="{{asset('images/2021/boton_archivo.png')}}" class="w-100 col-8" style="margin-top: -40px; margin-bottom: 30px;margin-left: 30%;">
                        </label>

                        <input id="imagen2" type="file" v-model="archivo2" ref="archivo2" @change="handleFileUpload2()" name="ok"/>
                    </div>
                    <div id="imgfoto2">
                        <img v-if="user.archivo_validacion_2" :src="user.archivo_validacion_2">
                    </div>
                    <div v-if="ok_imagen_2" class="text-danger text-center"><i class="fas fa-check-circle"></i> Imagen cargada correctamente.</div>
                    <div v-if="error_imagen_2" class="text-danger text-center"><i class="fas fa-times-circle"></i> Verifica el peso de la imagen.</div>
                    <div v-if="user.archivo_validacion_1 && user.archivo_validacion_2">
                        <img src="{{asset('images/2021/subir_archivos.png')}}" class="w-100 col-8" @click="enviarValidacion()">
                    </div>
                </div>
            </div>
        </div>
    </template>

@endsection
@section('scripts')

    <script>
        Vue.component('encuesta', {
            template: '#encuesta-template',
            props: ['p_preguntas', 'urls', 'p_user'],
            data: function () {
                return {
                    inicio: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    mostrarEncuesta: {
                        animacion: 'encuesta',
                        mostrar: false,
                        mostrarUltima: false
                    },
                    datosPersonales: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    referencia: '',
                    user: {},
                    numero: 0,
                    terminar: false,
                    preguntasCerradas: [],
                    preguntasAbiertas: [],
                    imostrarInicionformacion: '',
                    loading: false,
                    pago: '',
                    errors: [],
                    continuar: false,
                    pregunta: '',
                    errorAbierta: false,
                    sexo: 'Hombre',
                    sexotxt2: 'Hombre',
                    sexosvg: 'Hombre',
                    sexoheader: 'Hombre_header',
                    sexotext: 'Hombretext',
                    felicidades: false,
                    peso_ideal: 0,
                    archivo1: '',
                    archivo2: '',
                    ok_imagen_2: false,
                    error_imagen_2: false,
                    error_imagen_1: false,
                    ok_imagen_1: false,
                    errores_imagen: {
                        error_imagen_1: '',
                        error_imagen_2: '',
                    },
                    cuenta_circulo: 0,
                    espera: false,
                    mostrar_abiertas: false,
                }
            },
            methods: {
                comprobarAbiertas: function () {//comprueba errores con las preguntas (cerradas y abiertas)
                    let vm = this;
                    vm.errors = [];
                    axios.post('{{url('encuesta/validarAbiertas')}}', vm.preguntasAbiertas)
                        .then(function (respuesta) {
                            vm.mostrarCerradas();
                        })
                        .catch(function (errors) {
                            vm.errors = errors.response.data.errors;
                            vm.errors['siguiente'] = ['Llene todos los campos']
                        });
                },
                comprobarCerrada: function (pregunta, direccion) {
                    if (direccion == 1) {
                        let count = 0;
                        this.errors = [];
                        let findError = false;

                        if (pregunta.multiple == 0) {
                            for (let i = 0, opcion = pregunta.opciones; i < opcion.length && count == 0; i++) {
                                if (opcion[i].selected)//buscar que almenos uno este seleccionado
                                    count++;
                            }
                            if (count == 0) {
                                findError = true;
                                this.errors.seleccion = ['Seleccione al menos una opción'];
                            }
                        }
                        if (!findError) {
                            this.siguienteCerrada(pregunta);
                        }
                    } else {
                        this.anteriorCerrada(pregunta);
                    }
                },
                mostrarDatosPersonales: function () {
                    this.mostrarEncuesta.mostrar = false;
                    this.inicio.mostrar = true;
                },
                mostrarAbiertas: function () { //muestra la siguiente pantalla inicio con solo preguntasAbiertas
                    this.inicio.mostrar = false;
                    this.cuenta_circulo = 1;
                    this.mostrar_abiertas = true;
                    this.mostrarEncuesta.mostrar = true;
                    this.preguntasAbiertas.forEach(function (item, index) {
                        item.mostrar = true;
                    });
                    this.pregunta = "Peso y Estatura";
                    document.getElementById('imgheader').style.display = 'block';
                },
                mostrarCerradas: function () { //muestra las primera preguntas de preguntasCerradas y oculta las preguntasAbiertas
                    this.numero = 1;
                    this.mostrar_abiertas = false;
                    this.cuenta_circulo = this.cuenta_circulo+1;
                    this.mostrarEncuesta.mostrar = false;
                    this.preguntasAbiertas.forEach(function (item, index) {
                        item.mostrar = false;
                    });
                    if (this.preguntasCerradas.length != 0) {
                        this.pregunta = this.preguntasCerradas[this.numero].pregunta
                        this.preguntasCerradas[this.numero++].mostrar = true;
                    }
                },
                mostrarCerradasUno: function () { //muestra las primera preguntas de preguntasCerradas y oculta las preguntasAbiertas
                    this.numero = 0;
                    this.inicio.mostrar = false;
                    this.mostrar_abiertas = false;
                    this.cuenta_circulo = this.cuenta_circulo+1;
                    this.mostrarEncuesta.mostrar = false;
                    document.getElementById('imgheader').style.display = 'block';
                    this.preguntasAbiertas.forEach(function (item, index) {
                        item.mostrar = false;
                    });
                    if (this.preguntasCerradas.length != 0) {
                        this.pregunta = this.preguntasCerradas[this.numero].pregunta
                        this.preguntasCerradas[this.numero++].mostrar = true;
                    }
                },
                siguienteCerrada: function (pregunta) { //muestra la siguiente pregunta y cierra la anteriror
                    let vm = this;
                    pregunta.mostrar = false;
                    this.cuenta_circulo = this.cuenta_circulo+1;
                    if (vm.preguntasCerradas.length != vm.numero) {
                        this.preguntasCerradas[vm.numero].mostrar = true;
                        vm.numero++;
                        this.pregunta = this.preguntasCerradas[vm.numero-1].pregunta
                    } else {
                        vm.terminar = true;
                        this.pregunta = "Por favor llena esta información";
                        let respuestas = vm.preguntasAbiertas.concat(vm.preguntasCerradas);
                        /*axios.post("", {usuario: vm.user, respuestas: respuestas})
                            .then(function (respuesta) {
                                vm.terminar = true;
                                vm.pregunta = "Estamos casi listos..."
                            })
                            .catch(function (error) {
                                vm.terminar = false;
                            });*/
                    }
                },
                termina: function () { //muestra la siguiente pregunta y cierra la anteriror
                    var vm = this;
                    var respuestas = vm.preguntasAbiertas.concat(vm.preguntasCerradas);

                    vm.errors = [];
                    axios.post('{{url('encuesta/validarAbiertasdos')}}', vm.preguntasAbiertas)
                        .then(function (respuesta) {
                            vm.continuar = true;
                            axios.post("{{url('encuesta/save')}}", {usuario: vm.user, respuestas: respuestas})
                                .then(function (respuesta) {
                                    vm.espera = true;
                                    setTimeout(function(){
                                        vm.espera = false;
                                        vm.continuar = true;
                                        vm.terminar = false;
                                        vm.pregunta = "";
                                        location.reload();
                                        }, 3000);


                                    var actual = parseInt(respuesta.data.peso);
                                    var ideal = parseInt(respuesta.data.peso_ideal);
                                    var alcanzables = 0;

                                    if(actual < 50){
                                        if(actual < ideal){
                                            alcanzables = (actual+5);
                                        }else{
                                            alcanzables = (actual-5);
                                        }
                                    }
                                    if(actual >= 50 && actual < 60){
                                        if(actual < ideal){
                                            alcanzables = (actual+5);
                                        }else{
                                            alcanzables = (actual-5);
                                        }
                                    }
                                    if(actual >= 60 && actual < 70){
                                        if(actual < ideal){
                                            alcanzables = (actual+4);
                                        }else{
                                            alcanzables = (actual-3);
                                        }

                                    }
                                    if(actual >= 70 && actual < 80){
                                        if(actual < ideal){
                                            alcanzables = (actual+4.5);
                                        }else{
                                            alcanzables = (actual-3);
                                        }
                                    }
                                    if(actual >= 80 && actual <= 90){
                                        if(actual < ideal){
                                            alcanzables = (actual+3);
                                        }else{
                                            alcanzables = (actual-4);
                                        }
                                    }
                                    if(actual > 90){
                                        if(actual < ideal){
                                            alcanzables = (actual+2);
                                        }else{
                                            alcanzables = (actual-5);
                                        }
                                    }
                                    if(actual === ideal){
                                        alcanzables = (actual);
                                    }
                                    vm.peso_ideal = alcanzables;
                                })
                                .catch(function (error) {
                                });
                        })
                        .catch(function (errors) {
                            vm.errors = errors.response.data.errors;
                            vm.errors['siguiente'] = ['Llene todos los campos']
                        });
                },
                subir_archivo_1: function () { //muestra la siguiente pregunta y cierra la anteriror
                    var vm = this;
                    let formData = new FormData();
                    formData.append('file', this.archivo1);

                    vm.errors = [];
                    axios.post('{{url('encuesta/subirArchivo1')}}', formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (respuesta) {
                            vm.ok_imagen_1 = true;
                            vm.error_imagen_1 = false;
                            location.reload();
                        })
                        .catch(function (error) {
                            if (error.response) {
                                vm.ok_imagen_1 = false;
                                vm.error_imagen_1 = true;
                                if(error.response.status == '422'){
                                    this.error_imagen_1 = "okas";
                                    Vue.set(vm.errores_imagen, 'error_imagen_1', '123th avenue.');

                                    // subsequent changes can be done directly now and it will auto update
                                    vm.errores_imagen.error_imagen_1 = '<i class="fas fa-times-circle"></i> Verifica el peso de la imagen.';
                                }
                            }
                        });
                    setTimeout(function(){
                        var foto = '<img src="/images/'+vm.user.id+'_1.jpg">';
                        $('#imgfoto1').html(foto);
                    }, 1000);
                },
                subir_archivo_2: function () { //muestra la siguiente pregunta y cierra la anteriror
                    var vm = this;
                    let formData = new FormData();
                    formData.append('file', this.archivo2);

                    vm.errors = [];
                    axios.post('{{url('encuesta/subirArchivo2')}}', formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (respuesta) {
                            console.log(respuesta);
                            vm.ok_imagen_2 = true;
                            vm.error_imagen_2 = false;
                            location.reload();
                        })
                        .catch(function (error) {
                            if (error.response) {
                                vm.ok_imagen_2 = false;
                                vm.error_imagen_2 = true;
                                if(error.response.status == '422'){
                                    vm.error_imagen_2 = '<i class="fas fa-times-circle"></i> Verifica el peso de la imagen.';
                                }
                            }
                        });
                    setTimeout(function(){
                        var foto = '<img src="/images/'+vm.user.id+'_2.jpg">';
                        $('#imgfoto2').html(foto);
                    }, 1000);
                },
                enviarValidacion: function () { //muestra la siguiente pregunta y cierra la anteriror
                    axios.post('{{url('encuesta/enviarValidacion')}}',
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (respuesta) {
                            location.reload();
                        })
                        .catch(function (error) {
                        });
                },
                anteriorCerrada: function (pregunta) {
                    pregunta.mostrar = false;
                    this.numero--;
                    if (this.numero > 0) {
                        this.preguntasCerradas[this.numero-1].mostrar = true;
                        this.pregunta = this.preguntasCerradas[this.numero-1].pregunta
                    } else {
                        this.mostrarAbiertas();

                    }
                },
                select: function (pregunta, opcion) {
                    opcion.selected = !opcion.selected;
                    this.seleccionar(pregunta, opcion);
                    if (opcion.respuesta == 'Mujer' || opcion.respuesta == 'Hombre') {
                        this.sexo = opcion.respuesta;
                        this.sexosvg = opcion.respuesta + 'svg';
                        this.sexoheader = opcion.respuesta + '_header';
                        this.sexotext = opcion.respuesta + 'text';
                        this.sexotext2 = opcion.respuesta + 'text2';
                        document.getElementById("imgheader").style.setProperty('margin-top', '30px', 'important');
                        document.getElementById("imgheader").style.setProperty('margin-bottom', '30px', 'important');
                        document.getElementById("imgheader").style.setProperty('margin-left', '0px', 'important');
                        document.getElementById("imgheader").style.setProperty('width', '100%', 'important');
                        if (opcion.respuesta == 'Mujer') {
                            this.mostrarAbiertas();
                            document.getElementById("imgheader").src = "/images/2021/logo_movil_rosa.png";
                        }else {
                            this.mostrarAbiertas();
                            document.getElementById("imgheader").src = "/images/2021/logo_movil_azul.png";
                        }
                        this.comprobarCerrada(pregunta, 1);
                    }
                },
                seleccionar: function (pregunta, opcion) { //seleccionar solo una opcion
                    if (pregunta.multiple == 0) {
                        _.each(pregunta.opciones, function (opciones) { //volver todas las no seleccionadas false
                            if (opciones.respuesta != opcion.respuesta)
                                opciones.selected = false;
                        })
                        pregunta.respuesta = opcion.respuesta;
                    } else {
                        const index = pregunta.respuesta.indexOf(opcion.respuesta);
                        let respuesta = pregunta.respuesta;
                        //agregar respuesta si no esta en el arreglo,  si esta la respuesta entonces quitala
                        (index == -1 ? respuesta.push(opcion.respuesta) : respuesta.splice(index, 1));
                    }
                },
                handleFileUpload1(){
                    this.archivo1 = this.$refs.archivo1.files[0];
                    this.subir_archivo_1();
                },
                handleFileUpload2(){
                    this.archivo2 = this.$refs.archivo2.files[0];
                    this.subir_archivo_2();
                },
            },
            created: function () {
                let vm = this;
                document.getElementById('imgheader').style.display = 'none';
                vm.user = vm.p_user;
                vm.p_preguntas.forEach(function (item) { //separar Preguntas Abiertas de Cerradas
                    if (item.multiple == undefined)
                        vm.preguntasAbiertas.push(item);
                    else
                        vm.preguntasCerradas.push(item);
                });
                vm.inicio.mostrar = true;
                if(vm.user.encuestado){
                    vm.felicidades = true;
                }

                var actual = parseInt(this.user.peso);
                var ideal = parseInt(this.user.peso_ideal);
                var alcanzables = 0;

                if(actual < 50){
                    if(actual < ideal){
                        alcanzables = (actual+5);
                    }else{
                        alcanzables = (actual-5);
                    }
                }
                if(actual >= 50 && actual < 60){
                    if(actual < ideal){
                        alcanzables = (actual+5);
                    }else{
                        alcanzables = (actual-5);
                    }
                }
                if(actual >= 60 && actual < 70){
                    if(actual < ideal){
                        alcanzables = (actual+4);
                    }else{
                        alcanzables = (actual-3);
                    }

                }
                if(actual >= 70 && actual < 80){
                    if(actual < ideal){
                        alcanzables = (actual+4.5);
                    }else{
                        alcanzables = (actual-3);
                    }
                }
                if(actual >= 80 && actual <= 90){
                    if(actual < ideal){
                        alcanzables = (actual+3);
                    }else{
                        alcanzables = (actual-4);
                    }
                }
                if(actual > 90){
                    if(actual < ideal){
                        alcanzables = (actual+2);
                    }else{
                        alcanzables = (actual-5);
                    }
                }
                if(actual === ideal){
                    alcanzables = (actual);
                }
                vm.peso_ideal = alcanzables;
            },
            mostrarFelicidades() {
                this.felicidades = true;
                location.reload();

            }

        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

@endsection