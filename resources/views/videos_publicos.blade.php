@extends('layouts.app')
@section('header')
    <style>
        .ejercicio {
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 5px;
            width: 300px;
            margin: 5px;
        }

        input[type="file"] {
            display: none;
        }


        #masretos{
            width: 50% !important;
            margin-left: 25% !important;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        .disabled {
            background-color: #e1e1e8;
        }

        label {
            font-weight: bold;
        }

        hr {
            margin: 2px;
            margin-top: 20px;
        }

        #pendientes, #mostrarPendientes {
            background-color: #fff;
            border: 2px solid grey;
            position: absolute;
            bottom: 20px;
            right: 100px;
            text-align: center;
        }

        .feature {
            padding-bottom: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 550px;
        }

        .feature .img {
            cursor: pointer;
            height: auto;
        }

        .subinfo img {
            width: 100%;
            height: auto;
        }

        .subinfo div{
        }


        #features {
            background-color: #005D9C;
            padding: 20px;
            height: 480px;
        }

        #features .subinfo {
            font-size: .7rem;
            font-family: unitext_light;
            flex-grow: 1;
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

        @media only screen and (min-width: 1300px) {
            #features {
                height: 530px;
            }
        }

        @media only screen and (min-width: 1400px) {
            #features {
                height: 580px;
            }
        }

        @media only screen and (min-width: 1600px) {
            #features {
                height: 620px;
            }
        }

        @media only screen and (min-width: 1700px) {
            #features {
                height: 650px;
            }
        }

        @media only screen and (min-width: 1800px) {
            #features {
                height: 680px;
            }
        }

        @media only screen and (min-width: 1900px) {
            #features {
                height: 720px;
            }
        }
        .accesoSuple{
            font-size: 35px;
        }
        @media only screen and (max-width: 990px) {
            #features .subtitle {
                font-size: 2.5vw;
            }


            .accesoSuple{
                font-size: 15px;
            }

            .feature .img {
                cursor: pointer;
                height: 105%;
                width: 116%;
                margin-left: -8%;
            }

            .subinfo img {
                cursor: pointer !important;
                height: auto !important;
                width: 116% !important;
                margin-left: -8% !important;
            }
            .feature {
                padding-bottom: 80px;
                display: flex;
                flex-direction: column-reverse;
                justify-content: normal;
                height: auto;
            }

            #features .subinfo h6 {
                font-size: .7rem !important;
                line-height: 1.2 !important;
            }

            .comienza {
                font-size: .62rem;
            }
            #features {
                height: auto;
            }
        }

        @media only screen and (max-width: 800px) {
            .feature .subinfo {
                height: auto !important;
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
                margin-top: -100px;
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


            .feature .img {
                cursor: pointer;
                height: 105%;
                width: 116%;
                margin-left: -6.5%;
            }

            .subinfo .img {
                cursor: pointer !important;
                height: auto !important;
                width: 116% !important;
                margin-left: -8% !important;
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
                margin-top: -100px;
            }
            .section {
                margin-top: 20px;
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
        @media only screen and (max-width: 360px) {
            #pipoImg {
                margin-top: -89px;
            }
        }


        @media only screen and (min-width: 1920px) {

            .subinfo div {
                padding: 20px !important;
            }
        }

        @media only screen and (max-width: 1280px) {
            #features .subinfo h6 {
                font-size: .62rem;
                line-height: 1;
            }
        }

    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <div class="row justify-content-center">
                <inicio ></inicio>
            </div>
        </div>
    </div>

    <template id="videos-template">


        <div>


            <div class="card col-md-10 mx-auto">
                <div class="card-header">
                    <h1>Video de {{ $nombre }}</h1>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">

                        <video width="720" height="480" controls src="{{ $videos }}"
                               poster="{{asset('/img/header.png')}}" preload="none" controls="auto" class="col-md-10 mx-auto"  @ended="this.scrolling">
                            <source src="{{ $videos }}" type="video/mp4">
                        </video>

                    </div>
                    <br>
                </div>
            </div>


            <br>
            <br>
            <img src="{{asset('/images/imagesremodela/masretos.png')}}" id="masretos" style="width: 26%;margin-top: 50px;margin-bottom: 50px;margin-left: 37%;">
            <a href="https://suplementos.retoacton.com/" class="btn btn-primary btn-lg accesoSuple" style="color: white; background: #fb9b04; border-radius: 10px; font-weight: bolder;height: 80px;padding-top: 13px; font-family: unitext_bold_cursive; width: 50%; margin-left: 25%;">Ver suplementos</a>
            <br>
            <br>
            <div class="planesacton">
                <div id="features" class="d-flex flex-wrap mr-auto ml-auto">
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="comidasFeature" class="feature" @click="features.comidas=false" @mouseover=""
                             @mouseleave="features.comidas=true" onclick="location.href = '/register/2semanas';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.comidas" key="primero">
                                    <img id="comidasImg" class="img" src="{{asset('/images/imagesremodela/2semanasR.png')}}" width="100%">
                                    <h3 id="comidasSub" class="subtitle">
                                        <span></span>
                                        <span class="small text-lowercase"></span>
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="segundo">
                                    <img src="{{asset('/images/imagesremodela/2semanasR.png')}}">
                                    <div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="entrenamientoFeature" class="feature" @click="features.entrenamiento=false" @mouseover=""
                             @mouseleave="features.entrenamiento=true" onclick="location.href = '/register/4semanas';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.entrenamiento" key="first">
                                    <img id="entrenamientoImg" class="img" src="{{asset('/images/imagesremodela/4semanasR.png')}}"
                                         width="100%">
                                    <h3 id="entrenamientoSub" class="subtitle">
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('/images/imagesremodela/4semanasR.png')}}">
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="suplementosFeature" class="feature" @click="features.suplementos=false" @mouseover=""
                             @mouseleave="features.suplementos=true" onclick="location.href = '/register/8semanas';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.suplementos" key="first">
                                    <img id="suplementosImg" class="img" src="{{asset('/images/imagesremodela/8semanasR.png')}}"
                                         width="100%">
                                    <h3 id="suplementosSub" class="subtitle">
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('/images/imagesremodela/8semanasR.png')}}">
                                </div>
                            </transition>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-3 col-12">
                        <div id="videosFeature" class="feature" @click="features.videos=false" @mouseover=""
                             @mouseleave="features.videos=true" onclick="location.href = '/register/12semanas';">
                            <transition name="fade" mode="out-in">
                                <div v-if="features.videos" key="first">
                                    <img id="videosImg" class="img" src="{{asset('/images/imagesremodela/12semanasR.png')}}" width="100%">
                                    <h3 id="videosSub" class="subtitle">
                                    </h3>
                                </div>
                                <div v-else class="subinfo" key="second">
                                    <img src="{{asset('/images/imagesremodela/12semanasR.png')}}">
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>



        </div>

    </template>


@endsection
@section('scripts')
    <script>
        Vue.component('inicio', {
            template: '#videos-template',
            props: ['p_videos', 'p_categorias', 'p_pendientes'],
            data: function () {
                return {
                    features: {
                        comidas: true,
                        entrenamiento: true,
                        suplementos: true,
                        videos: true
                    },
                    categorias: [],
                    pendientes: [],
                    prueba: '',
                    errors: {},
                    loading: false,
                    buscando: false,
                    mostrarPendientes: true,
                    tarea:null,
                    categoria:null,
                    video_nuevo: ''
                }
            },
            methods: {
                checarFeature: function (feature, primero, segundo) {
                    let top_of_element = $("#" + feature + "Feature").offset().top + 300;
                    let bottom_of_element = top_of_element + $("#" + feature + "Feature").outerHeight() + 300;
                    let bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
                    let top_of_screen = $(window).scrollTop();
                    if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
                        this.features[feature]=false;
                        this.features[primero]=true;
                        this.features[segundo]=true;
                    }
                },
                scrolling: function () {
                    $('html,body').animate({
                        scrollTop: $("#masretos").offset().top
                    }, 'slow');
                }

            },
            created: function () {
            },
            mounted: function () {
                $(".planesacton").hide();
                $("#masretos").click(function(){
                    $(".planesacton").show();
                });
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
