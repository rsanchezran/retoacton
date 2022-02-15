@extends('layouts.app_semanas')
@section('header')
    <style>
        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        label.disabled{
            background-color: #f3f3f3;
        }

        input.required{
            border-color: #9c1f2d;
        }
        .stepwizard-step p {
            margin-top: 0px;
            color:#666;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .stepwizard-step button[disabled] {
            /*opacity: 1 !important;
            filter: alpha(opacity=100) !important;*/
        }
        .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
            opacity:1 !important;
            color:#bbb;
        }
        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content:" ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }
        .Mujer { /* Microsoft Edge */
            color: #B400B9 !important;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
            font-style: italic !important;
        }

        .Hombre { /* Microsoft Edge */
            color: #0080DD !important;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
            font-style: italic !important;
        }
        .btn-circle {
            width: auto;
            height: auto;
            text-align: center;
            padding: 6px 0;
            font-size: 15px;
            line-height: 1.428571429;
            border-radius: 0px;
        }
        .btn-success, .btn-default{
            background: transparent;
            border: 0px;
            color: #0080DD !important;
        }
        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: transparent;
            z-index: 0;
        }
        .btn-success:hover {
            color: #fff;
            background-color: transparent;
            border-color: #0080DD;
        }
        .btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active, .show > .btn-success.dropdown-toggle {
            color: #0080DD !important;
            border-bottom-color: #0080DD !important;
        }
        .stepwizard-step a{
            color: #c2c2c2 !important;
        }
        .stepwizard-step a:hover{
            color: #0080DD !important;
        }
        a.btn-success {
            color: #0080DD !important;
        }
        .cambiacolor{
            color: #0080DD !important;
            border-bottom: 3px solid #0080DD !important;
        }
        .multiselect__tag {
            background: #cccccc !important;
        }
        .multiselect__tags{
            background: rgb(245,245,245) !important;
            background: linear-gradient(180deg, rgba(245,245,245,1) 35%, rgba(166,166,166,1) 100%) !important;
            margin-bottom: 5px;
        }
        .multiselect__single {
            background: transparent !important;
        }
        .multiselect__input, .multiselect__single {
            background: transparent !important;
        }
        .multiselect__option--highlight {
            background: #0080DD !important;
        }
        .mostrar_o_publico {
            font-size: 10px !important;
            margin-left: 5px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .mostrar_o_publico input{
            padding: 3px;
        }
        .mostrar_o_publico input {
            padding: 14px;
            margin-left: 3px;
            margin-right: 3px;
        }
        .btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active, .show > .btn-success.dropdown-toggle {
            color: #0080DD !important;
            background-color: transparent !important;
            border-color: transparent !important;
        }
        .btn-success:focus, .btn-success.focus {
            color: #0080DD !important;
            background-color: transparent !important;
            border-color: transparent !important;
            box-shadow: 0 0 0 0rem rgb(1 1 1 / 50%) !important;
        }
        .btn-success:not(:disabled):not(.disabled):active:focus, .btn-success:not(:disabled):not(.disabled).active:focus, .show > .btn-success.dropdown-toggle:focus {
            box-shadow: 0 0 0 0rem rgb(1 1 1 / 50%) !important;
        }
        .multiselect__tags {
            min-height: 18px !important;
            font-size: 13px !important;
            border-radius: 13px !important;
        }
        .multiselect__tags {
            padding: 2px 40px 0 8px !important;
        }
        .multiselect__single {
            font-size: 13px !important;
        }
        .subir_foto {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
        .MujerEtapa a.btn-success {
            color: #B400B9 !important;
        }
        .cambiacolorMujer{
            color: #B400B9 !important;
            border-bottom: 3px solid #B400B9 !important;
        }
        .cambiacolorHombre{
            color: #0080DD !important;
            border-bottom: 3px solid #0080DD !important;
        }
        .btnagregar{
            background: #0080DD !important;
            border: 1px solid #0080DD;
            color: white;
        }
        .btncoins{
            background: #FFC300 !important;
            border: 1px solid #FFC300;
            color: white;
        }
        .tarjeta_precios{
            border: 1px solid rgba(0,0,0,.125);
            border-radius: 20px;
            margin-left: 0px;
            padding: 5px;
            box-shadow: 2px 2px 10px 2px rgb(0 0 0 / 13%);
            margin-right: 15px;
            color: #666666;
            font-size: 0.7em;
            width: 100%;
            background: white !important;
            margin-top: 4%;
        }
        .tarjeta_precios table tr {
            border-bottom: 1px solid #d2d2d2;
        }
        .tarjeta_precios table td{
            padding-bottom: 10px;
            padding-top: 10px;
            padding-left: 0px;
        }
        .tarjeta_precios table tr:last-child {
            border-bottom: 0px solid #d2d2d2;
        }
        .tarjeta_pagos{
            border-radius: 20px;
            margin-left: 0px;
            padding: 5px;
            margin-right: 15px;
            color: #000000;
            font-size: 0.7em;
            width: 100%;
            background: transparent !important;
            margin-top: 4%;
        }
        .tarjeta_pagos table tr {
            border-bottom: 1px solid #d2d2d2;
        }
        .tarjeta_pagos table td{
            padding-bottom: 10px;
            padding-top: 10px;
            padding-left: 0px;
        }
        .tarjeta_pagos table tr:last-child {
            border-bottom: 0px solid #d2d2d2;
        }
        #app{
            background: #0D47A1 !important;
        }
        #imgheader {
            width: 100% !important;
            margin-left: 0% !important;
            margin-top: 5% !important;
            margin-bottom: 10px !important;
        }
        .monto_{
            color: #FFED00;
        }
        .formaPago {
            border: 2px solid #e6e6e6;
            border-radius: 20px;
            padding: 10px;
            margin: 10px;
            align-content: center;
            text-align: center;
            background: rgb(245,245,245);
            background: linear-gradient(180deg, rgba(109,185,216,1) 0%, rgba(245,245,245,1) 100%);
            width: 110%;
            margin-left: -5% !important;
            color: #0D47A1;
            font-size: 2em;
        }
        .opps-ammount span{
            font-size: 30px;
        }
        .fechas_inicio{
            background: #29ABE2;
            border-radius: 10px;
            height: 50px;
            padding: 20px;
        }
        .fechas_inicio {
            background: #29ABE2;
            border-radius: 10px;
            height: 50px;
            margin-bottom: 15px;
            font-size: 19px;
            padding: 10px;
            color: white;
        }
        .mensajes_error{
            background: #F2645F;
            height: 50%;
            width: 100%;
            position: absolute;
        }
        .mensajes_error {
            background: #F2645F;
            height: 30%;
            width: 90%;
            position: absolute;
            z-index: 99999;
            margin-left: 5%;
            border-radius: 24px;
            font-size: 20px;
            color: white;
        }
        .cerrarbtn{
            position:absolute;
            top:0;
            right:15px;
            font-size: 20px;
        }
        .backtime{
            background: rgba(0, 0, 0, 0.5);
            padding: 25px;
            color: white;
            border-radius: 8px;
            font-size: 25px;
            width: 75px;
            max-width: 75px;
            min-width: 75px;
            margin-left: 7%;
        }
        #promo{
            color: #FFFFFF;
        }

        @media only screen and (max-width: 360px) {
            .tarjeta_precios{
                font-size: 0.6em !important;
            }
        }
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script>
        $(document).ready(function () {

            function obtenercoinsJS() {
                $('#btnCOINS').trigger('click');
            }

        });
    </script>
    @endsection
@section('content')
    <div id="vue">
        <div class="container">
            <cuenta :p_user="{{$user}}"></cuenta>
        </div>
    </div>
    <template id="cuenta-template">
        <div>
            <div v-if="pagadodiv" class="col-12 text-center" style="color: white;"><h2>Tu pago ha sido exitoso</h2></div>
            <div v-if="pagadodiv" class="card tarjeta_precios">
                <div class="card-body">
                    <h2 class="col-12 text-center" style="color: #0D47A1">¡Elige la fecha de inicio de tu nuevo reto!</h2>
                    <div class="col-8 offset-2 fechas_inicio text-center" id="fecha1" @click="setfecha(1)">@{{ fecha1 }}</div>
                    <div class="col-8 offset-2 fechas_inicio text-center" id="fecha2" @click="setfecha(2)">@{{ fecha2 }}</div>
                    <div class="col-8 offset-2 fechas_inicio text-center" id="fecha3" @click="setfecha(3)">@{{ fecha3 }}</div>
                </div>
            </div>
            <div v-if="user.pago_vitalicio == 0" class="text-center">
                <div v-if="mensajes_" class="col-12 mensajes_error">
                    <div class="fas fa-times cerrarbtn" @click="cerrarMensaje">X</div>
                    <div v-html="mensaje"></div>
                    <a href="#" @click="obtenercoins" class="col-6"><img src="{{asset('images/2021/obtener_coins.png')}}" class="col-8"></a>
                </div>

                <div v-if="menosdia && !obtenercoinsdiv" id="promo">
                    <img @click="obtenercoins" src="{{asset('images/2021/obtener_coins_n.png')}}" class="col-8 mb-5 mt-3">
                    <img src="{{asset('images/2021/unico_dia.png')}}" class="col-12">
                    <div class="col-12 countdown-reto">
                        <br>
                        <div class="col-12"><h6 class="text-center">Esta promoción termina en:</h6></div>
                        <br>
                        <div class="row ">
                            <div class="col-4 text-center">
                                <div class="backtime mb-2">@{{ horas }}</div>
                                <h6>HORAS</h6>
                            </div>
                            <div class="col-4 text-center">
                                <div class="backtime mb-2">@{{ minutos }}</div>
                                <h6>MINUTOS</h6>
                            </div>
                            <div class="col-4 text-center">
                                <div class="backtime mb-2">@{{ segundos }}</div>
                                <h6>SEGUNDOS</h6>
                            </div>
                        </div>
                    </div>
                    <img src="{{asset('images/2021/mensaje_promo.png')}}" class="col-12 mt-3">
                    <img src="{{asset('images/2021/aprovechar.png')}}" @click="muestraPromo" class="col-12">
                    <div class="card tarjeta_precios mb-5" v-if="mostrar_promo">
                        <div class="card-body">
                            <table class="">
                                <tbody>
                                <tr class="">
                                    <td class="col-6 text-left">Quiero agregar 1 semana</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_100.png')}}" style="width: 100%;"></td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(1)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 2 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_200.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(2)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 4 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_400.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(4)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 8 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_500.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(8)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 12 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_750.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(12)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 26 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_1500.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(26)">Agregar</button></td>
                                </tr>
                                <tr class="col-12">
                                    <td class="col-6 text-left">Quiero agregar 52 semanas</td>
                                    <td class="col-5 text-left"><img src="{{asset('images/2021/solo_2500.png')}}" style="width: 100%;"> </td>
                                    <td class="col-1"><button class="col-12 btnagregar" @click="agregar(52)">Agregar</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <img v-if="!obtenercoinsdiv && !pagadodiv && !menosdia" src="{{asset('images/2021/actualizar_plan.png')}}" class="col-8">
                <img class="col-12" v-if="obtenercoinsdiv" src="{{asset('images/2021/moneda_mensaje.png')}}">
                <div v-if="monto==0 && !pagadodiv && !menosdia" class="card tarjeta_precios">
                    <div class="card-body">
                        <table class="col-12" v-if="!obtenercoinsdiv">
                            <tbody>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 1 semana</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 200 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(1)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 2 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 400 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(2)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 4 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 800 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(4)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 8 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 1000 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(8)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 12 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 1500 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(12)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 26 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 3000 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(26)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 52 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/moneda_mini.png')}}" style="width: 10px;"> 5000 Acton coins</td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(52)">Agregar</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="obtenercoinsdiv" class="card tarjeta_precios">
                    <div class="card-body">
                        <table class="col-12" v-if="obtenercoinsdiv && monto==0" style="font-size: 1em;">
                            <tbody>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 100 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(100)">$100 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 200 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(200)">$200 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 500 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(500)">$500 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 1000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(1000)">$1000 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 2000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(2000)">$2000 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 3000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(3000)">$3000 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 4000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(4000)">$4000 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 5000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(5000)">$5000 mxn</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-3 text-left">OBTENER</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 10000 Acton coins</td>
                                <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(10000)">$1000 mxn</button></td>
                            </tr>
                            </tbody>
                        </table>
                        <a v-if="!obtenercoinsdiv" id="btnCOINS" href="#" @click="obtenercoins" class="col-12 mt-3"><img src="{{asset('images/2021/obtener_coins.png')}}" class="col-8 text-center"></a>
                </div>
            </div>
            <div class="tarjeta_pagos">
                <div v-if="monto>0" class="col-12 text-center">
                    <h2 style="color:white;">TOTAL A PAGAR</h2>
                    <h2 class="monto_">$ @{{ monto }}</h2>
                    <cobro_compra_coins_dos v-if="monto>0" ref="cobro" :cobro="''+monto" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                                    :llave="'{{env('CONEKTA_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                                    @terminado="terminado"></cobro_compra_coins_dos>
                </div>
            </div>
        </div>
            <div v-else class="text-center">
                <div class="card tarjeta_precios mb-5" >
                    <div class="card-body">
                        <table class="col-12">
                            <tbody>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 1 semana</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_100.png')}}" style="width: 100%;"></td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(1)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 2 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_200.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(2)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 4 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_400.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(4)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 8 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_500.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(8)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 12 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_750.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(12)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 26 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_1500.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(26)">Agregar</button></td>
                            </tr>
                            <tr class="col-12">
                                <td class="col-6 text-left">Quiero agregar 52 semanas</td>
                                <td class="col-5 text-left"><img src="{{asset('images/2021/solo_2500.png')}}" style="width: 100%;"> </td>
                                <td class="col-1"><button class="col-12 btnagregar" @click="agregar(52)">Agregar</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>


    </template>
@endsection
@section('scripts')
    <script>

        Vue.component('vue-multiselect', window.VueMultiselect.default)

        Vue.component(VueQrcode.name, VueQrcode);

        Vue.component('cuenta', {
            template: '#cuenta-template',
            props: ['p_user'],
            data: function () {
                return {
                    user: {},
                    errors: [],
                    loading: false,
                    loadingFoto: false,
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],
                    guardado: false,
                    fotografia: '{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id).'/'.rand(0,10)}}',
                    filtros: {
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: '',
                        estado: '0',
                        ciudad: '0',
                        cp: '0',
                        estado: '0',
                        colonia: '0',
                        ingresadosReto: '',
                        edad: 0,
                        gym: '',
                        intereses: '',
                        idiomas: '',
                        empleo: '',
                        estudios: '',
                        mensaje: '',
                        sexo: '',
                    },
                    finalizar: false,
                    value: null,
                    imgURL: '',
                    resultURL: '',
                    sexo_etapa: 'HombreEtapa',
                    obtenercoinsdiv: false,
                    pagadodiv: false,
                    mensajes_: false,
                    menosdia: false,
                    mostrar_promo: false,
                    coins: 0,
                    monto: 0,
                    fecha1: '',
                    fecha2: '',
                    fecha3: '',
                    ffecha1: '',
                    ffecha2: '',
                    ffecha3: '',
                    horas: 0,
                    minutos: 0,
                    segundos: 0,
                    semanas: 0,
                }
            },
            methods: {
                agregar: function (semanas) {
                    let vm = this;
                    vm.errors = [];
                    axios.get('/cuenta/agregarsemanas/'+semanas).then(function (response) {
                        if (response.data.status == 'ok'){
                            vm.pagadodiv = true;
                            vm.menosdia = false;
                            vm.fecha1 = 'Hoy mismo';
                            const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                                "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                            ];
                            var f1 = response.data.lunes.split('T');
                            f1 = f1[0].split('-');
                            vm.fecha2 = f1[2]+' '+monthNames[f1[1]-1];
                            var f3 = response.data.mes.split('-');
                            vm.fecha3 = '01 de '+monthNames[f3[1]-1];
                            vm.ffecha2 = response.data.lunes;
                            vm.ffecha3 = response.data.mes;
                            vm.semanas = semanas;
                        }else{
                            vm.mensaje = '<br>No cuentas con suficientes <br> Acton Coins ';
                            vm.mensajes_ = true;
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                },
                pagacoins: function (monto) {
                    let vm = this;
                    vm.monto = monto;
                },
                obtenercoins: function () {
                    let vm = this;
                    vm.obtenercoinsdiv = true;
                    vm.mensajes_ = false;
                    vm.menosdia = false;
                },
                cerrarMensaje: function () {
                    let vm = this;
                    vm.mensajes_ = false;
                },
                muestraPromo: function () {
                    let vm = this;
                    vm.mostrar_promo = true;
                },
                setfecha: function (fecha) {
                    let vm = this;
                    var f = '';
                    if (fecha == 1){
                        var todayDate = new Date().toISOString().slice(0, 10);
                        f = todayDate;
                    }
                    if (fecha == 2){ f = vm.ffecha2}
                    if (fecha == 3){ f = vm.ffecha3}
                    axios.get('/configuracion/setDia/'+vm.semanas+'/'+f).then(function (response) {
                        if (response.data.status == 'ok'){
                            window.location = '/home';
                        }else{
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                },
                countdown: function(){
                    var that = this;
                    setInterval(function() {
                        var diaInicioReto = that.user.inicio_reto;
                        var mdy = diaInicioReto.split(' ');
                        mdy = mdy[0].split('-');
                        var dia_inicio = new Date(mdy[0], mdy[1]-1, mdy[2], 8, 0, 0, 0);
                        var todayDate = new Date();
                        todayDate = new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate(), todayDate.getHours(), todayDate.getMinutes(), todayDate.getSeconds(), todayDate.getMilliseconds());
                        todayDate.setDate(todayDate.getDate());
                        const oneDay = 24 * 60 * 60 * 1000;
                        const diffDays = Math.round(Math.abs((dia_inicio - todayDate) / oneDay));
                        if(diffDays==0) {
                            var distance = todayDate - dia_inicio;
                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            that.dias_hasta = days;
                            that.horas = 24 - hours;
                            if (minutes == 0) {
                                that.minutos = 0;
                            } else {
                                that.minutos = 60 - minutes;
                            }
                            that.segundos = 60 - seconds;
                            if (hours >= 0 && minutes >= 0) {
                                that.menosdia = true;
                            } else {
                                that.menosdia = false;
                            }
                        }
                    }, 1000);
                },

            },
            mounted: function () {
                var vm = this;
                var that = this;
                var diaInicioReto = that.user.inicio_reto;
                var mdy = diaInicioReto.split(' ');
                mdy = mdy[0].split('-');
                var dia_inicio = new Date(mdy[0], mdy[1]-1, mdy[2], 8, 0, 0, 0);
                var todayDate = new Date();
                todayDate = new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate(), todayDate.getHours(), todayDate.getMinutes(), todayDate.getSeconds(), todayDate.getMilliseconds());
                todayDate.setDate(todayDate.getDate());
                const oneDay = 24 * 60 * 60 * 1000;
                const diffDays = Math.round(Math.abs((dia_inicio - todayDate) / oneDay));
                if(diffDays==0) {
                    var distance = todayDate - dia_inicio;
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    that.dias_hasta = days;
                    that.horas = 24 - hours;
                    if (minutes == 0) {
                        that.minutos = 0;
                    } else {
                        that.minutos = 60 - minutes;
                    }
                    that.segundos = 60 - seconds;
                    if (hours >= 0 && minutes >= 0) {
                        that.menosdia = true;
                    } else {
                        that.menosdia = false;
                    }
                }
                vm.countdown();
            },
            created: function () {
                var vm = this;
                this.user = this.p_user;

            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
