<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="https://www.google.com/recaptcha/api.js?onload=vueRecaptchaApiLoaded&render=explicit" async defer></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/multiitemcarousel.js') }}"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}"/>
    <style>
        @font-face {
            font-family: unitext;
            src: url("{{asset('fonts/unitext.ttf')}}");

        }

        @font-face {
            font-family: unitext_light;
            src: url("{{asset('fonts/unitext thin.ttf')}}");

        }

        @font-face {
            font-family: unitext_bold_cursive;
            src: url("{{asset('fonts/unitext bold cursive.ttf')}}");
        }

        @font-face {
            font-family: unitext_cursive;
            src: url("{{asset('fonts/unitext cursive.ttf')}}");
        }

        .container_foot_2021{
            background-image: url("{{asset('/images/2021/fondo_footer.png')}}");
            background-repeat: no-repeat;
            background-size: 100% auto;
            height: 500px;
            background-position-y: bottom;
        }

        #app {
            min-height: 100%;
            font-family: unitext;
        }

        .thin {
            font-family: unitext_light;
        }


        .links > a {
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
        }

        input[type=text], input[type=email] {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-bottom: 10px;
            width: 90%;
            box-sizing: border-box;
            color: #2C3E50;
            font-size: 13px;
        }

        #logo {
            position: absolute;
            top: 10px;
            left: 40px;
        }

        .navbar {
            padding: 40px;
            box-shadow: none;
            /*background-image: url('{{asset('/img/header_back2.png')}}');*/
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }

        main {
            margin-top: 0 !important;
        }

        .navbar-toggler {
            display: block;
            margin: 20px auto 0 auto;
        }

        #_op_data_r, #_op_data_antifraud {
            display: none;
        }

        .navbar-toggler {
            border: 0 !important;
        }

        #onda {
            width: 100%;
            position: absolute;
            top: 10px;
            left: 20px;
            z-index: 4;
        }

        @media only screen and (max-width: 420px) {
            .navbar {
                padding: 0;
                height: 0px;
            }

            .navbar-brand {
                padding: 0;
                height: 60px;
            }

            .navbar-nav {
                padding: 0;
            }

            .navbar-toggler {
                position: absolute;
                /*top: 50px;*/
                right: 2px;
            }

            .nav-item a {
                padding: 1px;
            }
            footer {
                background-color: white !important;
            }footer {
                 margin-top: auto;
                 padding: 0rem 0;
                 color: white;
             }


            main{
                background-size: 100%;
                background-attachment: fixed;
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        }
        @media only screen and (max-width: 400px) {
            .container_foot_2021{
                height: auto;
            }
        }
    </style>
    @yield('header')
</head>
<body class="h-100">
<div id="app" class="d-flex flex-column">
    {{--<img id="onda" src="{{asset('img/ondas.png')}}" alt="acton">--}}


    <main class="d-flex flex-column flex-grow-1 position-relative">
        @yield('content')
    </main>
</div>
<footer>
    <div class="container d-lg-none container_foot_2021">
        <div class="d-flex flex-wrap d-none d-md-block">
            <div class="col-sm-3" style="margin-top: 170px">
                <ul class="list-unstyled">
                    <li>
                        <a href="{{url('dudas')}}" style="margin-top: 10%;">
                            <img class="d-lg-none w-50" src="{{asset('images/2021/registro_1.png')}}" alt="First slide">
                        </a>
                    </li>
                </ul>
            </div>
            <br>
            <br>
            <div class="col-sm-3">
                <ul class="list-unstyled">
                    <li>
                        <a href="{{url('dudas')}}">
                            <img class="d-lg-none w-50" src="{{asset('images/2021/quienes.png')}}" alt="First slide">
                        </a>
                    </li>
                </ul>
            </div>
            <br>
            <br>
            <div class="col-sm-3">
                <ul class="list-unstyled">
                    <li>
                        <a href="{{url('dudas')}}">
                            <img class="d-lg-none" src="{{asset('images/2021/face_1.png')}}" alt="First slide" width="15">&nbsp;&nbsp;
                            <img class="d-lg-none" src="{{asset('images/2021/insta_1.png')}}" alt="First slide" width="25">
                        </a>
                    </li>
                </ul>
            </div>
            <br>
            <br>
            <br>
            <div class="col-sm-3">
                <img src="{{asset('images/2021/logo_blanco_1.png')}}" width="250">
            </div>
        </div>

    </div>


    <div class="container d-none d-md-block">
        <div class="d-flex flex-wrap d-none d-md-block">
            <div class="col-sm-3">
                <h5>ATENCIÓN A CLIENTES</h5>
                <ul class="list-unstyled">
                    @if(\Illuminate\Support\Facades\Auth::guest())
                        <li><a href="{{url('contacto')}}"><i class="fa fa-pencil"></i> Dudas</a></li>
                    @else
                        <li><a href="{{url('dudas')}}"><i class="fa fa-pencil"></i> Dudas</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>SÍGUENOS EN</h5>
                <ul class="list-unstyled">
                    <li><a href="https://www.facebook.com/FitnessPipoLandin"><i class="fab fa-facebook-square"></i>
                            Facebook</a></li>
                    <li><a href="https://www.instagram.com/pipolandin"><i class="fab fa-instagram"></i> Instagram</a>
                    </li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5>MÉTODOS DE PAGO</h5>
                <ul class="list-unstyled">
                    <li><i class="fab fa-cc-visa"></i> Visa</li>
                    <li><i class="fab fa-cc-mastercard"></i> Mastercard</li>
                    <li><i class="fab fa-paypal"></i> Paypal</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <img src="{{asset('img/retoactonblanco.png')}}" width="150">
            </div>
        </div>
    </div>
</footer>
@yield('scripts')
</body>
</html>
