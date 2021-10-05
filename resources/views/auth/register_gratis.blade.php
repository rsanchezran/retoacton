@extends('layouts.welcome')
@section('header')
    <style>

        #pago {
        }

        #imagentop{
            width: 50%;
        }


        #ultimodia{
            width: 100% !important;
            margin-left: -13.6% !important;
        }

        #preciogym{
            margin-left: -3% !important;
            width: 100% !important;

        }

        #ultimashoras1{
            width: 100%;
            margin-left: -13.7% !important;
        }
        #pagar{
            font-size: 50px !important;
            text-align: center !important;
        }

        #cobro_anterior:before {
            height: 33px !important;
        }
        #cobro_anterior {
            font-size: 2.2em !important;
        }
        #pagar span{
            font-size: 50px !important;
        }
        .container {
            max-width: 96% !important;
        }
        #vue{
            width: 100% !important;
        }

        .container .pt-5 {
            /* width: 133% !important; */
            padding-right: 0px !important;
            padding-left: 0 !important;
            margin-left: -1%;
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
            height: 0px;
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


        #features {
            background-color: #005D9C;
            padding: 20px;
            height: 480px;
            width: 106.7%;
            margin-left: -3px !important;
        }

        #features .subinfo {
            background-color: transparent;
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


        #descripcionsemanas{
            margin-left: -1.5%;
            width: 109.5%;
        }

        .modo{
            height: 2rem !important;
            width: auto !important;
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
            #features{
                height: auto;
            }
            #features .subtitle {
                font-size: 2.5vw;
            }

            .feature .img {
                cursor: pointer;
                height: 105%;
                width: 116%;
                margin-left: -8%;
            }

            .feature {
                padding-bottom: 30px;
                display: flex;
                flex-direction: column-reverse;
                justify-content: normal;
                height: auto;
            }

            #features .subinfo h6 {
                font-size: .7rem !important;
                line-height: 1.2 !important;
            }
            #imagentop{
                width: 100%;
            }

            .comienza {
                font-size: .62rem;
            }

            @media only screen and (max-width: 800px) {
                .feature .subinfo {
                    height: 300px !important;
                }

                .container {
                    max-width: 100% !important;
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

        @media only screen and (max-width: 1100px) {
            #descripcionsemanas {
            }
        }
        @media only screen and (max-width: 1000px) {
            #descripcionsemanas {
            }
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
            #50personas{
                width: 80% !important;
            }
            #ultimosdias{
                width: 80% !important;
            }
            #ultimodia{
                width: 100% !important;
                margin-left: -20.7% !important;
            }
        }

        @media only screen and (max-width: 900px) {
            #descripcionsemanas {
            }
        }

        @media only screen and (max-width: 950px) {
            #descripcionsemanas {
                margin-left: -4.5%;
                width: 110%;
            }
        }
        @media only screen and (max-width: 768px) {
            #descripcionsemanas{
            }
            .minutos img {
                width: 60% !important;
            }
            .horas img {
                width: 60% !important;
            }
            .segundos img {
                width: 60% !important;
            }
            .horareloj{
                font-size: 251px;
                color: white;
                position: absolute;
                letter-spacing: 63px;
                margin-left: 21px;
                margin-top: -66px;
            }
            .minreloj{
                font-size: 250px;
                color: white;
                position: absolute;
                letter-spacing: 52px;
                margin-left: 160px;
                margin-top: -40px;
                display: block;
                letter-spacing: 63px;
                /* margin-left: 21px; */
                margin-top: -66px;
            }
            .segundoreloj{
                font-size: 250px;
                color: white;
                position: absolute;
                letter-spacing: 52px;
                margin-left: 160px;
                margin-top: -40px;
                display: block;
                letter-spacing: 63px;
                /* margin-left: 21px; */
                margin-top: -66px;
            }
            #ultimashoras{
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 730px) {
            .horareloj{
                font-size: 230px;
                margin-top: -50px;
            }
            .minreloj{
                font-size: 230px;
                margin-top: -50px;
            }
            .segundoreloj{
                font-size: 230px;
                margin-top: -50px;
            }
        }

        @media only screen and (max-width: 700px) {
            .horareloj{
                font-size: 210px;
                letter-spacing: 40px;
                margin-top: -55px;
            }
            .minreloj{
                font-size: 210px;
                letter-spacing: 40px;
                margin-top: -55px;
            }
            .segundoreloj{
                font-size: 210px;
                letter-spacing: 40px;
                margin-top: -55px;
            }
        }

        @media only screen and (max-width: 650px) {
            /*.horareloj{
                font-size: 180px;
                margin-top: -45px;
                letter-spacing: 40px;
            }
            .minreloj{
                font-size: 180px;
                margin-top: -45px;
                letter-spacing: 40px;
                margin-left: 21%;
            }
            .segundoreloj{
                font-size: 180px;
                margin-top: -45px;
                letter-spacing: 40px;
                margin-left: 21%;
            }*/

            .minutos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horas img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundoreloj {
                font-size: 60px;
                margin-top: -135px;
                letter-spacing: 29px;
                margin-left: 65%;
            }
            .minreloj {
                font-size: 60px;
                margin-top: -136px;
                letter-spacing: 35px;
                margin-left: 35%;
            }

            .horareloj {
                font-size: 60px;
                margin-top: 0px;
                letter-spacing: 32px;
                margin-left: 3%;
            }
        }

        @media only screen and (max-width: 550px) {

            #testtitulo{
                width: 113.2%;
                margin-left: -5.4%;
            }
            #descripcionsemanas{
                margin-left: -5.5%;
                width: 113.5%;
            }

        }

        @media only screen and (max-width: 480px) {
            #descripcionsemanas {
            }
            .minutos{
                /*display: block !important;*/
            }
            /*.minutos img {
                width: 40% !important;
            }
            .horas img {
                width: 40% !important;
            }
            .segundos img {
                width: 40% !important;
            }
            .horareloj{
                font-size: 100px;
                letter-spacing: 25px;
                margin-left: 9px;
                margin-top: -25px;
            }
            .minreloj{
                font-size: 100px;
                letter-spacing: 25px;
                margin-left: 30%;
                margin-top: -25px;
            }
            .segundoreloj{
                font-size: 100px;
                letter-spacing: 25px;
                margin-left: 30%;
                margin-top: -25px;
            }*/


            .minutos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horas img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horareloj {
                font-size: 60px;
                color: white;
                position: absolute;
                letter-spacing: 29px;
                margin-left: 8px;
                margin-top: -4px;
            }
            .minreloj {
                font-size: 60px;
                color: white;
                letter-spacing: 27px;
                margin-left: 34%;
                margin-top: -132px;
            }
            .segundoreloj{
                font-size: 60px;
                color: white;
                letter-spacing: 31px;
                margin-left: 64%;
                margin-top: -131px;
            }
        }

        @media only screen and (max-width: 450px) {
            #descripcionsemanas {
            }
            #testtitulo{
                width: 120.2%;
                margin-left: -6.4%;
            }
            #descripcionsemanas{
                margin-left: -6.5%;
                width: 120.5%;
            }
            /*.minutos{
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
                letter-spacing: 30px;
                margin-left: 9px;
                margin-top: -19px;
            }
            .minreloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 25px;
                margin-left: 118px;
                margin-top: -19px;
            }
            .segundoreloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 25px;
                margin-left: 118px;
                margin-top: -19px;
            }*/

            .minutos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horas img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horareloj {
                font-size: 60px;
                color: white;
                position: absolute;
                letter-spacing: 25px;
                margin-left: 5px;
                margin-top: -8px;
            }
            .minreloj {
                font-size: 60px;
                color: white;
                letter-spacing: 22px;
                margin-left: 34%;
                margin-top: -123px;
            }
            .segundoreloj{
                font-size: 60px;
                color: white;
                letter-spacing: 22px;
                margin-left: 64%;
                margin-top: -123px;
            }
            #features {
                background-color: #005D9C;
                padding: 20px;
                width: 104%;
                margin-left: -1% !important;
            }
        }

        @media only screen and (max-width: 430px) {
            #testtitulo{
                width: 118.2%;
                margin-left: -10.4%;
            }
            #descripcionsemanas{
                margin-left: -6.5%;
                width: 113.5%;
            }
        }

        @media only screen and (max-width: 400px) {


            #cobro_anterior:before {
                width: 110% !important;
            }

            /*.minutos{
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
                font-size: 75px;
                letter-spacing: 25px;
                margin-left: 9px;
                margin-top: -15px;
            }
            .minreloj{
                font-size: 75px;
                letter-spacing: 25px;
                margin-left: 109px;
                margin-top: -15px;
            }
            .segundoreloj{
                font-size: 90px;
                color: white;
                position: absolute;
                letter-spacing: 25px;
                margin-left: 109px;
                margin-top: -15px;
            }*/
            .minutos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horas img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horareloj {
                font-size: 60px;
                color: white;
                position: absolute;
                letter-spacing: 19px;
                margin-left: 5px;
                margin-top: -11px;
            }
            .minreloj {
                font-size: 55px;
                color: white;
                letter-spacing: 21px;
                margin-left: 31%;
                margin-top: -117px;
            }
            .segundoreloj{
                font-size: 55px;
                color: white;
                letter-spacing: 21px;
                margin-left: 60%;
                margin-top: -117px;
            }
        }

        @media only screen and (max-width: 360px) {
            /*.minutos img {
                width: 40% !important;
            }
            .horas img {
                width: 40% !important;
            }
            .segundos img {
                width: 40% !important;
            }
            .horareloj {
                font-size: 78px;
                color: white;
                position: absolute;
                letter-spacing: 20px;
                margin-left: 5px;
                margin-top: -15px;
            }
            .minreloj {
                font-size: 78px;
                color: white;
                position: absolute;
                letter-spacing: 20px;
                margin-left: 29%;
                margin-top: -15px;
            }
            .segundoreloj{
                font-size: 78px;
                color: white;
                position: absolute;
                letter-spacing: 20px;
                margin-left: 29%;
                margin-top: -15px;
            }*/
            .minutos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horas img {
                width: 30% !important;
                margin-right: 2%;
            }
            .segundos img {
                width: 30% !important;
                margin-right: 2%;
            }
            .horareloj {
                font-size: 60px;
                color: white;
                position: absolute;
                letter-spacing: 12px;
                margin-left: 1px;
                margin-top: -15px;
            }
            .minreloj {
                font-size: 55px;
                color: white;
                letter-spacing: 16px;
                margin-left: 30%;
                margin-top: -106px;
            }
            .segundoreloj{
                font-size: 55px;
                color: white;
                letter-spacing: 16px;
                margin-left: 59%;
                margin-top: -106px;
            }
            #descripcionsemanas {
                margin-left: -5.5%;
                width: 113.5%;
            }
            #testtitulo {
                width: 119.2%;
                margin-left: -11%;
            }
        }

        #ultimashoras1{
            display: none !important;
        }


        @media only screen and (min-width: 1580px) {
            #features {
                background-color: #005D9C;
                padding: 20px;
                height: 730px;
                width: 106.7%;
                margin-left: -3px !important;
            }
        }
        @media only screen and (min-width: 1980px) {
            #features {
                background-color: #005D9C;
                padding: 20px;
                height: 900px;
                width: 106.7%;
                margin-left: -3px !important;
            }
        }
        @media only screen and (min-width: 1350px) {
            .horareloj {
                letter-spacing: 33px;
                margin-left: 8px;
                margin-top: -16px;
            }
            .minreloj {
                letter-spacing: 33px;
                margin-left: 8px;
                margin-top: -16px;
            }
            .segundoreloj {
                letter-spacing: 33px;
                margin-left: 8px;
                margin-top: -16px;
            }
        }



        @media only screen and (min-width: 1450px) {
            .horareloj {
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
            .minreloj {
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
            .segundoreloj {
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
        }


        @media only screen and (min-width: 1550px) {
            .horareloj {
                font-size: 94px;
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
            .minreloj {
                font-size: 94px;
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
            .segundoreloj {
                font-size: 94px;
                letter-spacing: 40px;
                margin-left: 13px;
                margin-top: -11px;
            }
        }


        @media only screen and (min-width: 1650px) {
            .horareloj {
                font-size: 110px;
                letter-spacing: 45px;
                margin-top: -15px;
            }
            .minreloj {
                font-size: 110px;
                letter-spacing: 45px;
                margin-top: -15px;
            }
            .segundoreloj {
                font-size: 110px;
                letter-spacing: 45px;
                margin-top: -15px;
            }
        }


        @media only screen and (min-width: 1750px) {
            .horareloj {
                font-size: 118px;
            }
            .minreloj {
                font-size: 118px;
            }
            .segundoreloj {
                font-size: 118px;
            }
        }


        @media only screen and (min-width: 1850px) {
            .horareloj {
                font-size: 129px;
                margin-top: -22px;
            }
            .minreloj {
                font-size: 129px;
                margin-top: -22px;
            }
            .segundoreloj {
                font-size: 129px;
                margin-top: -22px;
            }
        }


        @media only screen and (min-width: 1920px) {
            .horareloj {
                font-size: 135px;
            }
            .minreloj {
                font-size: 135px;
            }
            .segundoreloj {
                font-size: 135px;
            }
        }


        @media only screen and (min-width: 2000px) {
            .horareloj {
                font-size: 175px;
                margin-top: -40px;
            }
            .minreloj {
                font-size: 175px;
                margin-top: -40px;
            }
            .segundoreloj {
                font-size: 175px;
                margin-top: -40px;
            }
        }

        select {
            color: #333333 !important;
            border-radius: 6px !important;
            background: rgb(245,245,245) !important;
            background: linear-gradient(
                    180deg, rgba(245,245,245,1) 35%, rgba(166,166,166,1) 100%) !important;
        }

        .custom-select {
            background: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3e%3cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3e%3c/svg%3e") no-repeat right 0.75rem center/8px 10px !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        ::-webkit-input-placeholder {
            font-style: italic;
            font-weight: lighter;
            font-family: 'Arial', sans-serif;
        }
        :-moz-placeholder {
            font-style: italic;
            font-weight: lighter;
            font-family: 'Arial', sans-serif;
        }
        ::-moz-placeholder {
            font-style: italic;
            font-weight: lighter;
            font-family: 'Arial', sans-serif;
        }
        :-ms-input-placeholder {
            font-style: italic;
            font-weight: lighter;
            font-family: 'Arial', sans-serif;
        }

    </style>
@endsection
@section('content')
    <div id="vue" class="">
        <registro class=""
                  :medios="{{$medios}}"
        ></registro>
    </div>

    <template id="registro-template">
        <div class="">
        <div id="header" align="center" class="">
            <img src="{{asset('images/2021/logo_degradado.png')}}" id="imagentop" class="w-100">
            <br>
            <br>
            <img v-if="encontrado_url && mensaje_ref==''" src="{{asset('images/2021/texto_2_semanas.png')}}" id="imagentop" style="width: 90% !important;">
            <br>
            <br>
            <h3 v-if="encontrado_url && mensaje_ref==''" class="col-12 text-center">
                @{{ referencia }}
            </h3>
            <h3 v-if="mensaje_ref!=='' && encontrado_url" class="col-12 text-center">
                @{{ mensaje_ref }}
            </h3>
            <br>
            <img v-if="!encontrado_url" src="{{asset('images/2021/mensaje_gratis.png')}}" id="imagentop" class="w-75">
            <br v-if="!encontrado_url">
            <br v-if="!encontrado_url">
            <br v-if="!encontrado_url">
        </div>
            <div align="center" class="col-12 text-center" style="" v-if="!mostrarDatos">
                <select class="form-control " v-model="informacion.medio" @change="seleccionarMedio" id="seleccionamedio">
                    <option value="" disabled>¿Cómo te enteraste de reto acton?</option>
                    <option v-for="medio in medios" :value="medio">@{{medio}}</option>
                </select>
                <div v-if="informacion.medio=='Por medio de un amigo'" class="text-left" style="" id="referenciaCampo">
                    <span style="color: #929292">
                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                    </span>
                    <input class="form-control col-6" v-model="informacion.codigo" placeholder="Ingrésa su código"
                           @blur="buscarReferencia()" maxlength="7">
                    <form-error name="codigo" :errors="errors"></form-error>
                    <div v-if="encontrado!==null && !encontrado_url">
                        <span v-if="encontrado && !encontrado_url">El código que ingresaste corresponde a:
                            <i style="font-size:1.1rem" class="font-weight-bold">@{{ referencia }}</i>
                        </span>
                        <span v-if="!encontrado && !encontrado_url"
                              class="font-weight-bold">[No se encontró al alguien con ese código de referencia]</span>
                    </div>
                </div>
                <div v-if="informacion.medio=='Por medio de un entrenador'" class="text-left">
                    <span style="color: #929292">
                        Si conoces el código de referencia de tu amigo, por favor ingrésalo aquí
                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                    </span>
                    <input class="form-control col-6" v-model="informacion.codigo" placeholder="REFERENCIA"
                           @blur="buscarReferenciaCoach()" maxlength="7">
                    <form-error name="codigo" :errors="errors"></form-error>
                    <div v-if="encontrado">
                        <span v-if="encontrado">El código que ingresaste corresponde a:
                            <i style="font-size:1.1rem" class="font-weight-bold">@{{ referencia }}</i>
                        </span>
                        <span v-else
                              class="font-weight-bold">[No se encontró a alguien con ese código de referencia]</span>
                    </div>
                </div>
                <div v-if="informacion.medio != ''" class="text-left">
                    <input class="form-control" placeholder="Nombre" v-model="informacion.nombres">
                    <form-error name="nombres" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Apellido" v-model="informacion.apellidos">
                    <form-error name="apellidos" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Teléfono" v-model="informacion.telefono">
                    <form-error name="telefono" :errors="errors"></form-error>
                    <input type="email" class="form-control" placeholder="Correo electrónico" v-model="informacion.email">
                    <form-error name="email" :errors="errors"></form-error>
                    <input type="password" class="form-control" placeholder="Elige tu contraseña" v-model="informacion.password">
                    <form-error name="password" :errors="errors"></form-error>
                    <input type="password" class="form-control" placeholder="Confirma tu contraseña" v-model="informacion.password_dos">
                    <form-error name="password_dos" :errors="errors"></form-error>
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
                                  class="font-weight-bold">[No se encontró a alguien con ese código de referencia]</span>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <button class="btn btn-primary acton" @click="saveContacto" :disabled="loading">
                            Continuar
                            <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </div>
            </div>
            <br>


            <div class="col-12"  v-if="mostrarDatos">
                <div id="header" align="center" class="col-12 text-center">
                    <img src="{{asset('images/2021/logo_movil_azul.png')}}" id="imagentop" class="w-75">
                    <br>
                    <br>
                    <img src="{{asset('images/2021/mensaje_ingreso.png')}}" id="imagentop" class="w-75">
                    <br>
                    <br>
                    <br>
                </div>
                <div class="col-12 text-center">
                    <h3>Usuario: @{{ informacion.email }}</h3>
                    <h3>Contraseña: @{{ informacion.password }}</h3>
                    <form method="POST" action="{{ route('login') }}" >
                        @csrf
                        <div class="form-group row  text-right justify-content-end">

                            <div class="col-12 justify-content-end">
                                <input id="email" placeholder="Correo Electronico" type="email" class="form-control @error('email') is-invalid @enderror col-12 " name="email" value="{{ old('email') }}" required autocomplete="email" autofocus style="width: 100%; border-color: #1565C0;">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-12">
                                <input id="password" placeholder="Contraseña" type="password" class="form-control @error('password') is-invalid @enderror col-12 " name="password" required autocomplete="current-password"  style="width: 100%; border: 1px solid #1565C0;">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember"> Recordar credenciales</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" class="" style="border: 0px; background-color: white;">
                                    <img class="d-lg-none w-75" src="{{asset('images/2021/ontinuar.png')}}" alt="First slide">
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
                    referencia: null,
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
                    usuario: '',
                    pass: '',
                    mostrarDatos: false,
                    mensaje_ref: '',
                    codigo_url: false,
                    encontrado_url: false,

            }
            },
            computed: {
                time: function(){
                    return vm.date.format('hh:mm:ss');
                }
            },
            mounted: function () {

                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                console.log(params.get("codigo"));
                if(params.get("codigo")){
                    this.informacion.medio = "Por medio de un amigo";
                    this.informacion.codigo = params.get("codigo");
                    this.codigo_url = true;
                    this.buscarReferencia();
                    document.getElementById('seleccionamedio').style.display = 'none';
                    setTimeout(function(){
                        document.getElementById('referenciaCampo').style.display = 'none';
                    }, 1000);
                }else{
                    this.codigo_url = false;
                    document.getElementById('seleccionamedio').style.display = 'block';
                    setTimeout(function(){
                        document.getElementById('referenciaCampo').style.display = 'block';
                    }, 1000);
                }

                var lasCookies = document.cookie;


                lasCookies = lasCookies.split(';');
                if(lasCookies.length > 2) {
                    this.informacion.medio = "Otro";
                    for (var l = 0; l < lasCookies.length; l++) {
                        console.log(lasCookies[l]);
                        if(lasCookies[l].indexOf('nombre') != -1){
                            this.informacion.nombres = lasCookies[l].replace("nombre=", "");
                        }
                        if(lasCookies[l].indexOf('telefono') != -1){
                            this.informacion.telefono = lasCookies[l].replace("telefono=", "");
                        }
                        if(lasCookies[l].indexOf('email') != -1){
                            this.informacion.email = lasCookies[l].replace("email=", "");
                        }
                        if(lasCookies[l].indexOf('apellidos') != -1){
                            this.informacion.apellidos = lasCookies[l].replace("apellidos=", "");
                        }
                    }
                }

            },
            methods: {
                terminado: function () {
                    window.location.href = "{{url('/login')}}";
                },
                metodoPagoLocal: function (pago) {
                    this.$refs[pago].showModal();
                },
                buscarReferencia: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    this.informacion.tipocontacto = 1;
                    axios.get('{{url('buscarReferencia')}}/' + vm.informacion.codigo).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        if(vm.codigo_url==true){
                            vm.encontrado_url = true;
                            vm.encontrado = false;
                        }else{
                            vm.encontrado = true;
                            vm.encontrado_url = false;
                        }
                        vm.mensaje_ref = ''
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.codigo_url==true){
                            vm.encontrado_url = true;
                            vm.encontrado = false;
                        }else{
                            vm.encontrado = true;
                            vm.encontrado_url = false;
                        }
                        vm.mensaje_ref = 'No se encontró a alguien con ese código de referencia'
                        if(vm.sent){
                            //vm.saveContacto();
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
                buscarReferenciaCoach: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    this.informacion.tipocontacto = 1;
                    axios.get('{{url('buscarReferenciaCoach')}}/' + vm.informacion.codigo+'/'+vm.informacion.email).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        vm.encontrado = true;
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.sent){
                            //vm.saveContacto();
                        }
                        vm.loading = false;
                        vm.encontrado = false;
                    });
                },
                saveContacto: function () {

                    document.cookie = "nombre="+this.informacion.nombres;
                    document.cookie = "apellidos="+this.informacion.apellidos;
                    document.cookie = "telefono="+this.informacion.telefono;
                    document.cookie = "email="+this.informacion.email;

                    let vm = this;
                    this.loading = true;
                    this.errors = {};
                    this.mostrarDatos = true;
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
                    if (this.informacion.password==''){
                        this.errors.password = ['La contraseña es obligatoria'];
                    }
                    if (Object.keys(this.errors).length == 0) {
                        axios.post("{{url("crearCuentaFree")}}", this.informacion).then(function (response) {
                            vm.sent = true;
                            vm.loading = false;
                            if (response.data.status == 'ok') {

                                this.usuario = this.informacion.email;
                                this.pass = this.informacion.password;

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
                    var randomnumber = Math.floor(Math.random() * (20000 - 2000 + 1)) + 2000;
                    setTimeout(function(){
                        $('#soloquedan').html('<h2>Quedan sólo 13 lugares con descuento</h2>');
                    }, randomnumber);
                    setTimeout(function(){
                        $('#soloquedan').html('<h2>Quedan sólo 12 lugares con descuento</h2>');
                    }, 120000);

                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>
@endsection
