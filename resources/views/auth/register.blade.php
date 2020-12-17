@extends('layouts.welcome')
@section('header')
    <style>
        #pagar{
            width: 250px;
            font-size: 50px !important;
        }
        #pagar span{
            font-size: 50px !important;
        }
        .container {
            max-width: 96% !important;
        }
        #infoPago label {
            margin-bottom: 0;
            font-size: 35px !important;
        }
        .form-control {
            border: 0;
            border-radius: 0;
        }

        input.form-control {
            margin: 10px 0;
        }

        input[type="email"] {
            width: 100%;
        }

        a.btn-primary {
            font-size: 15pt;
            background-color: #1c4565;
            border-color: #1c4565;
            padding: 2% 20%;
        }

        a.btn-primary:hover {
            background-color: #2c628c;
        }

        #vue {
            background-color: #f2f2f2;
            background-image: url("{{asset('images/imagesremodela/rayo.png')}}");
            background-repeat: no-repeat;
            background-position: center;
            background-position-x: -7;
            background-position-y: 0;
        }

        h6 {
        }

        @media only screen and (max-width: 420px) {
            .container {
                margin-left: 0;
                margin-right: 0;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .detalle{
            font-size: 1rem;
        }

        #pago{
            display: block;
            margin: auto
        }

        .paypal-buttons-context-iframe{
            min-width: 100% !important;
        }
        .acton{
            width: 200px;
            font-family: unitext_bold_cursive;
            padding: 10px;
            color:#FFF;
        }
        #tipo_programa{
            display: none;
        }        .fade-enter-active, .fade-leave-active {
                     transition: opacity .1s;
                 }
        .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
            opacity: 0;
        }

        video {
            height: 200px;
            width: 100%;
        }

        .info {
            padding: 20px 10px;
            background-color: #f6f6f6;
        }

        .section {
            margin-top: 60px;
        }

        .big {
            font-size: 1.4em;
        }

        .bigger {
            font-size: 2em;
            line-height: 1 !important;
        }

        .biggest {
            font-size: 3em;
            line-height: 1 !important;
        }

        .subtitle {
            color: #8F9191;
            display: inline;
            text-align: justify;
            text-transform: uppercase;
            font-size: 1.2em;
            font-family: unitext_light;
            font-weight: bold;
        }

        .subinfo {
            text-align: justify;
            font-size: 1em;
            height: 230px;
        }

        .btn-primary {
            font-size: 1em;
            background-color: #ff9900;
            border: 1px solid #ff9900;
            padding: 0.5em 2em;
            text-transform: uppercase;
            font-weight: bold;
        }

        btn-primary:hover {
            background-color: #2c628c;
        }

        .feature {
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }

        .feature .img {
            height: 100%;
        }

        .subinfo img {
            width: 100%;
            height: 100%;
        }

        #features {
            background-color: #005D9C;
            padding: 20px;
        }

        #features .subinfo {
            background-color: #F0F0F0;
            font-size: .7rem;
            font-family: unitext_light;
            flex-grow: 1;
            height: 300px !important;
        }

        #features .subtitle {
            font-family: unitext;
            position: absolute;
            width: 70%;
            top: 40%;
            left: 0;
            right: 0;
            margin-left: auto;
            margin-right: auto;
            display: flex;
            flex-direction: column;
            z-index: 2;
            color: #454545;
            text-justify: distribute;
            text-align: center;
            font-weight: bold;
            font-size: 1.5vw;
        }
        #features .subinfo h6 {
            font-size: 0.7rem;
            color: #1b1e21;
            font-weight: bold !important;
            line-height: 1.5;
        }

        .rey {
            background-color: #015289;
            padding: 20px;
            color: #fff;
        }

        .turquesa {
            color: #01ffff
        }

        .gris {
            color: #9c9c9c;
        }

        #verde {
            background-color: #93c468;
            color: #fff;
        }

        .marino {
            background-color: #003450;
        }

        .azul {
            color: #4BA9E6 !important;
        }

        .testimonio {
            text-align: center;
            color: #fff;
            padding: 10px;
        }

        #test {
            background-image: url('{{asset('img/backrayo.png')}}');
            background-repeat: no-repeat;
            background-position: top left;
            background-size: auto;
            /*padding-bottom: 20px;*/
        }

        #testtitulo {
            background-image: url('{{asset('img/logo reto.png')}}');
            background-repeat: no-repeat;
            background-size: 150px;
            background-position: top right;
            width: 108.4%;
            margin-left: -4.3%;
        }

        .modo{
            height: 2rem !important;
            width: auto !important;
        }

        #pipo {
            background-color: #013451;
            color: #FFF;
            background-image: url("{{asset('img/lineas.png')}}");
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 100%;
            min-height: 400px;
        }

        #curva {
            background-image: url("{{asset('img/radius.png')}}");
            background-size: 100% 100%;
            height: 60px;
            width: 100%;
        }

        #quote {
            background-image: url('{{asset('img/comillas.png')}}');
            background-repeat: no-repeat;
            background-size: 200px;
            background-position: 200px 120px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        #cree {
            width: 320px;
            text-align: center;
            margin: 1px auto;
            line-height: 1.2;
        }

        #cree p {
            padding: 1px;
            margin: 1px;
        }

        #desicion {
            margin-top: 80px;
            margin-bottom: 100px;
        }

        #ranking{
            margin-top: 80px;
        }

        #finanzas {
            padding: 20px 0px 80px 40px;
            background-image: url("{{asset('img/rayoback.png')}}");
            background-size: 100% 100%;
            background-repeat: no-repeat;
            color: #fff;
        }

        #bonus {
            margin-left: 140px;
            margin-top: 120px;
            z-index: 10;
        }

        #bonus p {
            margin: 1px;
            text-align: justify;
        }

        #chica {
            margin-top: -190px;
            margin-left: -213px;
            height: 1000px;
        }

        #pipoImg {
            width: 430px;
            margin-top: -120px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        #monitores h6 {
            margin: auto;
            width: 70%;
            text-align: center;
        }

        #monitores img {
            display: block;
            margin: auto;
        }

        .monitor {
            margin: 10px 0px;
        }

        #garantia {
            text-align: left;
            font-size: 2.8rem;
        }

        #garantiaDiv {
            margin-left: -50px
        }

        #garantiaDes {
            font-family: unitext_light;
            color: black !important;
        }

        #soñarlo {
            font-size: 3.0em;
            font-weight: bold;
        }

        #hacerlo {
            font-size: 3.8em;
        }

        #meta {
            margin-top: 40px;
            font-size: 1.2rem;
        }

        #inscripcion {
            margin-left: 0px
        }

        #ganar {
            font-size: 2.8rem;
            color: #000000 !important;

        }

        .semana p {
            margin: 1px 8px;
            padding: 1px 12px;
            line-height: 1;
        }

        .semana h6 {
            margin-bottom: 1px;
            color: #000;
        }

        #mejora {
            margin-left: -40px
        }

        #ganadores {
            font-size: 1.8em;
            margin-bottom: 1px
        }

        #todospueden {
            font-size: 1.7rem
        }

        #compensacion {
            width: 80%;
            font-size: 1rem;
        }

        #otrobonus {
            font-size: 1.4rem;
        }

        #trofeobonus {
            margin-right: 10px;
        }

        #frase {
            margin: 40px auto;
        }

        .comienza {
            font-size: .75rem;
        }

        @media only screen and (max-width: 990px) {
            #features .subtitle {
                font-size: 2.5vw;
            }

            #features .subinfo h6 {
                font-size: .7rem !important;
                line-height: 1.2 !important;
            }

            .comienza {
                font-size: .62rem;
            }

            @media only screen and (max-width: 800px) {
                .feature .subinfo {
                    height: 300px !important;
                }

                #testtitulo {
                    background: none;
                }

                #pipo {
                    min-height: auto;
                }

                #frase {
                    margin: 20px auto;
                }

                #pipoImg {
                    width: 210px !important;
                    margin-top: -70px;
                }

                #pipoDiv {
                    padding-left: 0;
                }

                #quote {
                    background-size: 100px !important;
                    align-items: flex-start;
                    background-position: top;
                }

                #finanzas {
                    background-image: url("{{asset('img/rayobackmovil.png')}}");
                }

                #cree {
                    width: 100%;
                    text-align: right;
                    font-size: 0.65rem;
                }

                .momento {
                    font-size: 1.2rem !important;
                    text-align: center;
                }

                #cree h6 {
                    font-size: 0.8rem;
                }

                #chica {
                    margin-top: -80px;
                    margin-left: -400px;
                    height: 700px;
                }

                #garantiaDiv {
                    display: block;
                    margin-top: 20px;
                    margin-left: auto;
                    margin-right: auto;
                }

                #garantiaDes {
                    padding-top: 10px;
                    text-align: center;
                }

                #soñarlo {
                    font-size: 1.5rem;
                }

                #hacerlo {
                    width: 50%;
                    font-size: 2.2em;
                }

                #transformar h6 {
                    font-size: .75rem;
                    margin-bottom: 1px;
                }

                #transformar p {
                    font-size: 0.65rem;
                    line-height: 1.4;
                }

                #meta {
                    width: 62%;
                    margin-top: 40px;
                    font-size: .9rem;
                }

                #inscripcion {
                    margin-left: 0px
                }

                #garantia {
                    text-align: center;
                    font-size: 3rem;
                }

                #ganar {
                    font-size: 3.2rem;
                    text-align: center;
                }

                .semana {
                    box-shadow: none;
                    border-bottom: 2px solid #AAA9A9;
                    padding: 5px;
                    height: 45px;
                    margin-top: 10px;
                }

                .semana h6 {
                    font-size: .7rem;
                }

                .semana p {
                    font-size: .65rem;
                }

                #mejora {
                    margin-top: 40px;
                    margin-left: -20px
                }

                #mejora h3 {
                    font-size: 1.5rem !important;
                    margin-bottom: 1px;
                }

                #ganadores {
                    font-size: 1.2em;
                }

                #todospueden {
                    font-size: 1rem;
                }

                #compensacion {
                    width: 80%;
                    font-size: 0.65rem;
                }

                #otrobonus {
                    font-size: 1.2rem;
                }

                #trofeobonus {
                    margin-right: 10px;
                }

                #features .subtitle {
                    font-size: 3.5vw;
                }

                #bonus {
                    margin-left: 40px;
                    margin-top: 100px;
                }
            }

            @media only screen and (max-width: 420px) {
                #bonus {
                    margin-top: 140px;
                    margin-left: 10px;
                    margin-right: -300px;
                }

                #verdadero {
                    padding: 0;
                }

                #tituloFeature {
                    padding-left: 5px;
                    padding-right: 5px;
                }

                #features .subtitle {
                    font-size: 4.5vw !important;
                }

                #features .subinfo h6 {
                    font-size: .7rem !important;
                    line-height: 1.2 !important;
                }

                #cree {
                    font-size: .55rem !important;
                }

                #pipoImg {
                    width: 160px !important;
                    margin-top: -60px;
                }

                #frase {
                    margin: 0px auto;
                }

                #garantia {
                    font-size: 2.5rem;
                }

                #ganar {
                    font-size: 2.8rem;
                }
            }
        }

        @media only screen and (min-width: 1920px) {

        }

        @media only screen and (max-width: 1280px) {
            #features .subinfo h6 {
                font-size: .62rem;
                line-height: 1;
            }
        }

        .horareloj{
            font-size: 94px;
            color: white;
            position: absolute;
            letter-spacing: 25px;
            margin-left: 8px;
            margin-top: -19px;
            font-variant-numeric: tabular-nums;
        }
        .minreloj{
            font-size: 94px;
            color: white;
            position: absolute;
            letter-spacing: 25px;
            margin-left: 8px;
            margin-top: -19px;
            font-variant-numeric: tabular-nums;
        }
        .segundoreloj{
            font-size: 94px;
            color: white;
            position: absolute;
            letter-spacing: 25px;
            margin-left: 8px;
            margin-top: -19px;
            font-variant-numeric: tabular-nums;
        }

        @media only screen and (max-width: 1000px) {
            .horareloj{
                font-size: 64px;
                letter-spacing: 27px;
                margin-left: 8px;
            }
            .minreloj{
                font-size: 64px;
                letter-spacing: 14px;
                margin-left: 4px;
            }
            .segundoreloj{
                font-size: 64px;
                letter-spacing: 10px;
                margin-left: 4px;
            }
            #masretos{
                width: 50% !important;
                margin-left: 25% !important;
            }
            #metodos{
                width: 70% !important;
                margin-left: 0% !important;
            }
            #ultimashoras1{
                margin-left: -5.7% !important;
            }
            .satisfaccion_total{
                margin-left: -25% !important;
                width: 135% !important;
            }
        }

        @media only screen and (max-width: 768px) {
            #descripcionsemanas{
                width: 119.6% !important;
            }
            .horareloj{
                font-size: 40px;
                letter-spacing: 20px;
                margin-left: 7px;
                margin-top: -10px;
            }
            .minreloj{
                font-size: 40px;
                letter-spacing: 12px;
                margin-left: 4px;
                margin-top: -10px;
            }
            .segundoreloj{
                font-size: 40px;
                letter-spacing: 10px;
                margin-left: 6px;
                margin-top: -10px;
            }
        }

        @media only screen and (max-width: 480px) {
            .minutos{
                display: block !important;
            }
            .minutos img {
                width: 40% !important;
            }
            .horas img {
                width: 40% !important;
            }
            .segundos img {
                width: 40% !important;
            }
            .horareloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 40px;
                margin-left: 17px;
                margin-top: -19px;
            }
            .minreloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 30px;
                margin-left: 9px;
                margin-top: -19px;
            }
            .segundoreloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 30px;
                margin-left: 11px;
                margin-top: -19px;
            }
        }

        @media only screen and (max-width: 360px) {
            .horareloj{
                font-size: 70px;
                color: white;
                position: absolute;
                letter-spacing: 40px;
                margin-left: 7px;
                margin-top: -15px;
            }
            .minreloj{
                font-size: 70px;
                color: white;
                position: absolute;
                letter-spacing: 31px;
                margin-left: 6px;
                margin-top: -15px;
            }
            .segundoreloj{
                font-size: 70px;
                color: white;
                position: absolute;
                letter-spacing: 25px;
                margin-left: 8px;
                margin-top: -15px;
            }
        }

    </style>
@endsection
@section('content')
    <div id="vue" class="container flex-center">
        <registro class="pt-5" :urls="{{$urls}}" :medios="{{$medios}}"></registro>
    </div>

    <template id="registro-template">
        <div class="container">
            <div align="center">
                <div id="header" align="center">
                    <!--img src="images/imagesremodela/rayo.png" id="imgreto"-->
                    <br>
                    <br>
                </div>
                <h5 class="text-left" style="color:#0080DD">Antes de comenzar nos gustaría saber un poco más sobre
                    ti </h5>
                <select class="form-control" v-model="informacion.medio" @change="seleccionarMedio">
                    <option value="" disabled>¿Cómo te enteraste del reto acton?</option>
                    <option v-for="medio in medios" :value="medio">@{{medio}}</option>
                </select>
                <div v-if="informacion.medio=='Por medio de un amigo'" class="text-left">
                    <span style="color: #929292">
                        Si conoces el código de referencia de tu amigo, por favor ingrésalo aquí
                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                    </span>
                    <input class="form-control col-6" v-model="informacion.codigo" placeholder="REFERENCIA"
                           @blur="buscarReferencia()" maxlength="7">
                    <form-error name="codigo" :errors="errors"></form-error>
                    <div v-if="encontrado!==null">
                        <span v-if="encontrado">El código que ingresaste corresponde a:
                            <i style="font-size:1.1rem" class="font-weight-bold">@{{ referencia }}</i>
                        </span>
                        <span v-else
                              class="font-weight-bold">[No se encontró al alguien con ese código de referencia]</span>
                    </div>
                </div>
                <div v-if="informacion.medio != ''" class="text-left">
                    <input class="form-control" placeholder="Nombres" v-model="informacion.nombres">
                    <form-error name="nombres" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Apellidos" v-model="informacion.apellidos">
                    <form-error name="apellidos" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Teléfono" v-model="informacion.telefono">
                    <form-error name="telefono" :errors="errors"></form-error>
                    <input type="email" class="form-control" placeholder="Correo electrónico" v-model="informacion.email"
                           @blur="saveContacto" @keypress.enter="saveContacto">
                    <form-error name="email" :errors="errors"></form-error>
                    <select class="form-control" v-model="informacion.tipo" id="tipo_programa">
                        <option value="84">12 semanas</option>
                        <option value="56">8 semanas</option>
                        <option value="28">4 semanas</option>
                        <option value="14">2 semanas</option>
                    </select>
                    <div v-if="informacion.medio=='Por medio de un gimnasio o tienda de suplementos'" class="text-left">
                        <span style="color: #929292">
                            Si conoces el código de referencia de tu tienda o gimnasio, por favor ingrésalo aquí
                            <i v-if="loading" class="far fa-spinner fa-spin"></i>
                        </span>
                        <input class="form-control col-6" v-model="informacion.codigo" placeholder="REFERENCIA"
                               @blur="buscarReferenciaTienda()" maxlength="7">
                        <form-error name="codigo" :errors="errors"></form-error>
                        <div v-if="encontrado!==null">
                            <span v-if="encontrado">El código que ingresaste corresponde a:
                                <i style="font-size:1.1rem" class="font-weight-bold">@{{ referencia }}</i>
                            </span>
                            <span v-else
                                  class="font-weight-bold">[No se encontró al alguien con ese código de referencia]</span>
                        </div>
                    </div>
                    <div class="mt-4 text-left">
                        <button class="btn btn-primary acton" @click="saveContacto" :disabled="loading">
                            Continuar
                            <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                            <i v-else class="fa fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <br>


            <div v-show="sent" id="pago" class="text-center col-12">
                <div style="margin-top: 40px;">
                    <div style="margin-top:60px; margin-bottom: 70px">
                        <img src="{{asset("images/imagesremodela/malla.png")}}" width="100%" id="descripcionsemanas" style="margin-left: -68px;width: 112.6%;">
                    </div>
                </div>
                <div style="margin-top: 40px;">
                    <div style="margin-top:0px; margin-bottom: 70px">
                        <img src="{{asset("img/satisfaccion_total.png")}}" width="120%" style="margin-left: -10%;">
                    </div>
                </div>
                <div v-if="informacion.medio=='Por medio de un gimnasio o tienda de suplementos'">
                    <img src="{{asset("images/imagesremodela/preciogym.png")}}" width="112%" id="preciogym" style="margin-left: -6%">
                </div>
                <div style="margin-top: 40px;">
                    <div style="margin-top:60px; margin-bottom: 70px">
                        <div v-if="informacion.medio!=='Por medio de un gimnasio o tienda de suplementos'">
                            <img src="{{asset("images/imagesremodela/50personas.png")}}" width="100%" id="50personas" style="width: 50%;">
                            <img src="{{asset("images/imagesremodela/ultimosdias.png")}}" width="100%" id="ultimosdias" style="width: 50%;">
                        </div>
                    </div>
                </div>
                <div v-show="mensaje!=''">
                    <h6 class="detalle">@{{ mensaje }}</h6>
                </div>
                <div v-show="mensaje==''">
                    <h6 class="detalle">¡Gracias por compartirnos tus datos,</h6>
                    <h6 class="detalle"> nos encantará ayudarte!</h6>
                    <h6 class="detalle"> El costo para unirte y tener los </h6>
                    <h6 class="detalle"> beneficios del <b class="text-uppercase">Reto Acton</b> es de:
                    </h6>
                    <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                        <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                               estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                               :caracter="true"></money>
                    </label>
                    <div id="infoPago" v-if="descuento>0">
                        <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                            el </label>
                        <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                        <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive" v-if="descuento=='{{env('DESCUENTO')}}'">ÚLTIMO DIA</label>
                    </div>
                    <div id="pagar">
                        <div>a sólo</div>
                        <div style="font-size: 1.5rem; margin-left: 5px">
                            <money :cantidad="''+monto" :caracter="true" :decimales="0"
                                   estilo="font-size:1.5em; font-weight: bold"></money>
                        </div>
                    </div>
                    <br>
                </div>
                <br>

                <div v-if="informacion.medio!=='Por medio de un gimnasio o tienda de suplementos'">
                    <div id="apps" v-if="this.informacion.tipo == 14 || this.informacion.tipo == 84">
                        <div  v-if="hr" class="horasrestantes" style="display: inline;">
                            <div class="horas" style="display: inline;">
                                <span class="horareloj" style="">@{{hr}}</span>
                                <img src="images/imagesremodela/nhoras.png" style="width:15%;">
                            </div>
                            <div class="minutos" class="minutos" style="display: inline;">
                                <span class="minreloj" style="">@{{min}}</span>
                                <img src="images/imagesremodela/nminutos.png" style="width:15%;">
                            </div>
                            <div class="segundos" class="segundos" style="display: inline;">
                                <span class="segundoreloj" style="">@{{seg}}</span>
                                <img src="images/imagesremodela/nsegundos.png" style="width:15%;">
                            </div>
                        </div>
                    </div>
                </div>



                <div v-if="informacion.medio!=='Por medio de un gimnasio o tienda de suplementos'">
                    <div style="margin-top: 40px;">
                        <div style="margin-top:60px; margin-bottom: 70px">
                            <img src="{{asset("images/imagesremodela/ultimashoras1.png")}}" width="100%" id="ultimashoras1" style="width: 100%;margin-left: -2.7%;">
                            <img src="{{asset("images/imagesremodela/ultimodia1.png")}}" width="100%" id="ultimodia1" style="width: 50%;">
                            <img src="{{asset("images/imagesremodela/ultimodia.png")}}" width="100%" id="ultimodia" style="width: 100%;margin-left: -2.7%;">
                            <img src="{{asset("images/imagesremodela/ultimashoras.png")}}" width="100%" id="ultimashoras" style="width: 50%;">
                            <div v-if="this.informacion.tipo == 14" class="text-center text-danger" id="soloquedan"><h2>Quedan sólo 3 lugares</h2></div>
                        </div>
                    </div>
                </div>


                <img src="{{asset('images/imagesremodela/metodos.png')}}" id="metodos" style="width: 35%;margin-top: 50px;margin-bottom: 50px;margin-left:0%;">

                <div class="pasarelas">
                    <h6 style="color: #000;">Estas son las formas de realizar tu pago de manera segura</h6>
                    <cobro ref="cobro" :cobro="''+monto" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                           :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                           @terminado="terminado"></cobro>
                </div>

                <div>
                    <div id="" class="" style="padding-top:100px; padding-bottom:10px;">
                        <div id="testtitulo" class="">
                            <img src="{{asset('img/historias_exito.jpg')}}" width="100%">
                        </div>
                        <div class="col-10 col-sm-4 col-md-4 text-center d-block mr-auto ml-auto mt-8"
                             style="margin-bottom:40px">
                        </div>
                    </div>
                </div>
            </div>


            <img src="{{asset('images/imagesremodela/masretos.png')}}" id="masretos" style="width: 26%;margin-top: 50px;margin-bottom: 50px;margin-left: 37%;">
            <div class="planesacton">
                <div id="features" class="d-flex flex-wrap mr-auto ml-auto">
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="comidasFeature" class="feature" @click="features.comidas=false" @mouseover="features.comidas=false"
                             @mouseleave="features.comidas=true" onclick="location.href = '/register?q=14';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.comidas" key="primero">
                                    <img id="comidasImg" class="img" src="{{asset('/img/2semanasblanco.png')}}" width="100%">
                                    <h3 id="comidasSub" class="subtitle">
                                        <span>Plan de 2 semanas </span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="segundo">
                                    <img src="{{asset('img/2semanas.png')}}">
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="entrenamientoFeature" class="feature" @click="features.entrenamiento=false" @mouseover="features.entrenamiento=false"
                             @mouseleave="features.entrenamiento=true" onclick="location.href = '/register?q=28';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.entrenamiento" key="first">
                                    <img id="entrenamientoImg" class="img" src="{{asset('/img/4semanasblanco.png')}}"
                                         width="100%">
                                    <h3 id="entrenamientoSub" class="subtitle">
                                        <span>Plan 4 semanas</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('img/4semanas.png')}}">
                                    <div>
                                    <!--h6 class="text-justify">Puedes elegir si deseas entrenar en el gym, desde la comodidad
                                            de tu hogar o en el lugar donde te encuentres, ya que dentro de tu sesión tienes un
                                            botón en el cual puedes cambiar tu rutina a </h6>
                                        <h6 class="font-weight-bold text-center" style="font-family: unitext;">Modo: GYM o CASA</h6>
                                        <div class="col-sm-10 col-md-10 col-lg-10 col-xl-10 d-flex justify-content-between p-0 ml-auto mr-auto">
                                            <img class="modo" src="{{asset('img/Boton01.png')}}"/>
                                            <img class="modo" src="{{asset('img/Boton02.png')}}"/>
                                        </div-->
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="suplementosFeature" class="feature" @click="features.suplementos=false" @mouseover="features.suplementos=false"
                             @mouseleave="features.suplementos=true" onclick="location.href = '/register?q=56';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.suplementos" key="first">
                                    <img id="suplementosImg" class="img" src="{{asset('/img/8semanasblanco.png')}}"
                                         width="100%">
                                    <h3 id="suplementosSub" class="subtitle">
                                        <span>Plan de 8 semanas</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('img/8semanas.png')}}">
                                    <div>
                                        <!--h6>Te diremos cuáles son los suplementos adecuados con las dosis óptimas para alcanzar
                                            de una manera más efectiva y rápida el objetivo de que deseas <span
                                                class="font-weight-bold"
                                                style="font-family:unitext;">siempre cuidando tu salud.</span></h6-->
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="videosFeature" class="feature" @click="features.videos=false" @mouseover="features.videos=false"
                             @mouseleave="features.videos=true" onclick="location.href = '/register?q=84';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.videos" key="first">
                                    <img id="videosImg" class="img" src="{{asset('/img/12semanasblanco.png')}}" width="100%">
                                    <h3 id="videosSub" class="subtitle">
                                        <span>Plan de 12 semanas</span>
                                        <span class="small text-lowercase">ver más</span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('img/12semanas.png')}}">
                                    <div>
                                        <!--h6><span class="font-weight-bold text-center" style="font-family: unitext">Más de 500 videos de alta resolución </span>
                                            en tu sesión con explicación de cada ejercicio para que puedas ver la técnica
                                            correcta de cada movimiento. Cada uno de los ejercicios trae video, ya sea de casa o
                                            gym.</h6-->
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>
        </div>
    </template>
@endsection
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
    <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
    <script src="https://momentjs.com/downloads/moment.js"></script>

    <script>
        Vue.component('registro', {
            template: '#registro-template',
            props: ['urls', 'medios'],
            data: function () {
                return {
                    errors: [],
                    sent: false,
                    srcVideo: '',
                    informacion: {
                        nombres: '',
                        apellidos: '',
                        email: '',
                        telefono: '',
                        medio: '',
                        codigo: '',
                        tipocontacto: 0
                    },
                    features: {
                        comidas: true,
                        entrenamiento: true,
                        suplementos: true,
                        videos: true
                    },
                    loading: false,
                    encontrado: null,
                    referencia: '',
                    original: '0',
                    monto: '0',
                    descuento: '0',
                    horas: '0',
                    horasdos: '0',
                    mensaje: '',
                    date: '',
                    hr: '',
                    min: '',
                    seg: '',
                    mostrarReloj: true,
                }
            },
            computed: {
                time: function(){
                    return vm.date.format('hh:mm:ss');
                }
            },
            mounted: function () {

                this.$nextTick(function () {
                    let urlParams = new URLSearchParams(window.location.search);
                    let d = urlParams.get('q');
                    if(d !== null){
                        this.informacion.tipo = d;
                    }else{
                        this.informacion.tipo = 14;
                        d = 14;
                    }
                    $(".planesacton").hide();
                    $("#masretos").click(function(){
                        $(".planesacton").show();
                    });
                    $(".pasarelas").hide();
                    $("#metodos").click(function(){
                        $(".pasarelas").show();
                    });
                    d = d/7;
                    $("#ultimashoras").hide();
                    $("#ultimashoras1").hide();
                    $("#50personas").hide();
                    $("#ultimosdias").hide();
                    $("#ultimodia1").hide();
                    $("#ultimodia").hide();
                    $(".soloquedan").hide();
                    if(d == 2){
                        $("#imgreto").attr('src', 'images/imagesremodela/reto2.png');
                        $("#descripcionsemanas").attr('src', 'images/imagesremodela/2semanas.png');
                    }
                    if(d == 4){
                        $("#imgreto").attr('src', 'images/imagesremodela/reto4.png');
                        $("#descripcionsemanas").attr('src', 'images/imagesremodela/4semans.png');
                        $(".soloquedan").show();
                    }
                    if(d == 8){
                        $("#imgreto").attr('src', 'images/imagesremodela/reto8.png');
                        $("#descripcionsemanas").attr('src', 'images/imagesremodela/8semanas.png');
                    }
                    if(d == 12){
                        $("#ultimashoras").show();
                        $("#imgreto").attr('src', 'images/imagesremodela/reto12.png');
                        $("#descripcionsemanas").attr('src', 'images/imagesremodela/12semanas.png');
                    }
                })
            },
            methods: {
                terminado: function () {
                    window.location.href = "{{url('/login')}}";
                },
                buscarReferencia: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    this.informacion.tipocontacto = 1;
                    axios.get('{{url('buscarReferencia')}}/' + vm.informacion.codigo).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        vm.encontrado = true;
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.sent){
                            vm.saveContacto();
                        }
                        vm.loading = false;
                        vm.encontrado = false;
                    });
                },
                buscarReferenciaTienda: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    this.informacion.tipocontacto = 1;
                    axios.get('{{url('buscarReferenciaTienda')}}/' + vm.informacion.codigo+'/'+vm.informacion.email).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        vm.encontrado = true;
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.sent){
                            vm.saveContacto();
                        }
                        vm.loading = false;
                        vm.encontrado = false;
                    });
                },
                saveContacto: function () {
                    if(this.informacion.tipo == 14){
                        $("#ultimashoras1").show();
                    }
                    if(this.informacion.tipo == 28){
                        $("#50personas").show();
                    }
                    if(this.informacion.tipo == 56){
                        $("#ultimosdias").show();
                        $("#ultimodia").show();
                    }
                    let vm = this;
                    this.loading = true;
                    this.errors = {};
                    this.informacion.nombres = this.informacion.nombres.trim();
                    this.informacion.apellidos = this.informacion.apellidos.trim();
                    this.informacion.email = this.informacion.email.trim();
                    this.informacion.telefono = this.informacion.telefono.trim();
                    this.informacion.codigo = this.informacion.codigo.trim();
                    if(this.informacion.nombres==''){
                        this.errors.nombres = ['El nombre es obligatorio'];
                    }
                    if(this.informacion.apellidos==''){
                        this.errors.apellidos = ['Los apellidos son obligatorios'];
                    }
                    if (this.informacion.telefono==''){
                        this.errors.telefono = ['El teléfono es obligatorio'];
                    }
                    if (this.informacion.email==''){
                        this.errors.email = ['El correo electrónico es obligatorio'];
                    }
                    if (Object.keys(this.errors).length == 0) {
                        axios.post("{{url("saveContacto")}}", this.informacion).then(function (response) {
                            vm.sent = true;
                            vm.loading = false;
                            if (response.data.status == 'ok') {
                                vm.original = response.data.original;
                                vm.monto = response.data.monto;
                                vm.descuento = response.data.descuento;
                                vm.horas = response.data.horas;
                                if(response.data.horas > 0) {
                                    vm.horasdos = moment("2017-03-13 " + response.data.horas + ':30');
                                    vm.date = moment("2017-03-13 " + response.data.horas + ':30').format('HH:mm:ss')


                                    setInterval(() => {
                                        vm.horasdos = moment(vm.horasdos.subtract(1, 'seconds'));
                                        vm.date = moment(vm.horasdos).format('HH:mm:ss');
                                        vm.hr = moment(vm.horasdos).format('HH');
                                        vm.min = moment(vm.horasdos).format('mm');
                                        vm.seg = moment(vm.horasdos).format('ss');
                                        if (vm.seg == 1){
                                            var myBurger = document.querySelector('.segundos');
                                            myBurger.classList.toggle('is-active');
                                        }
                                    }, 1500);

                                }

                                vm.$refs.cobro.configurar(
                                    vm.informacion.nombres,
                                    vm.informacion.apellidos,
                                    vm.informacion.email,
                                    vm.informacion.telefono,
                                    vm.informacion.codigo,
                                );




                            }
                            vm.mensaje = response.data.mensaje;
                            if (vm.mensaje == 'Este usuario ya pertenece al RETO ACTON.'){
                                $("#metodos").hide();
                            }else{
                                $("#metodos").show();
                            }
                        }).catch(function (error) {
                            vm.sent = false;
                            vm.loading = false;
                            vm.errors = error;
                        });
                    }else{
                        this.sent = false;
                        this.loading = false;
                    }



                },
                seleccionarMedio: function () {
                    this.informacion.codigo = '';
                    if(this.sent){
                        this.saveContacto();
                    }
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>
@endsection
