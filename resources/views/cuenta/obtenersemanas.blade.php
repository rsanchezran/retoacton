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
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <script>
        $(document).ready(function () {

            function obtenercoinsJS() {
                $('#btnCOINS').trigger('click');
            }

            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');
                allPrevBtn = $('.prevBtn');

            allWells.hide();

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);
                if($(this).attr('href') == '#step-1'){
                    $("#pasouno").addClass('cambiacolor');
                    if($("#pasouno").hasClass('MujerEtapa')){
                        $("#pasouno").addClass('cambiacolorMujer');
                    }else{
                        $("#pasouno").addClass('cambiacolorHombre');
                    }
                    $("#pasodos").removeClass('cambiacolor');
                    $("#pasotres").removeClass('cambiacolor');
                    $("#pasotres").removeClass('cambiacolorMujer');
                    $("#pasotres").removeClass('cambiacolorMujer');
                    $("#pasodos").removeClass('cambiacolorMujer');
                    $("#pasodos").removeClass('cambiacolorMujer');
                    $("#pasotres").removeClass('cambiacolorHombre');
                    $("#pasotres").removeClass('cambiacolorHombre');
                    $("#pasodos").removeClass('cambiacolorHombre');
                    $("#pasodos").removeClass('cambiacolorHombre');
                }
                if($(this).attr('href') == '#step-2'){
                    $("#pasouno").removeClass('cambiacolor');
                    $("#pasodos").addClass('cambiacolor');
                    $("#pasotres").removeClass('cambiacolor');
                    $("#pasouno").removeClass('cambiacolorMujer');
                    $("#pasouno").removeClass('cambiacolorMujer');
                    $("#pasotres").removeClass('cambiacolorMujer');
                    $("#pasotres").removeClass('cambiacolorMujer');
                    $("#pasouno").removeClass('cambiacolorHombre');
                    $("#pasouno").removeClass('cambiacolorHombre');
                    $("#pasotres").removeClass('cambiacolorHombre');
                    $("#pasotres").removeClass('cambiacolorHombre');
                    if($("#pasodos").hasClass('MujerEtapa')){
                        $("#pasodos").addClass('cambiacolorMujer');
                    }else{
                        $("#pasodos").addClass('cambiacolorHombre');
                    }
                }
                if($(this).attr('href') == '#step-3'){
                    $("#pasouno").removeClass('cambiacolor');
                    $("#pasodos").removeClass('cambiacolor');
                    $("#pasotres").addClass('cambiacolor');
                    $("#pasouno").removeClass('cambiacolorMujer');
                    $("#pasouno").removeClass('cambiacolorMujer');
                    $("#pasodos").removeClass('cambiacolorMujer');
                    $("#pasodos").removeClass('cambiacolorMujer');
                    $("#pasouno").removeClass('cambiacolorHombre');
                    $("#pasouno").removeClass('cambiacolorHombre');
                    $("#pasodos").removeClass('cambiacolorHombre');
                    $("#pasodos").removeClass('cambiacolorHombre');
                    if($("#pasotres").hasClass('MujerEtapa')){
                        $("#pasotres").addClass('cambiacolorMujer');
                    }else{
                        $("#pasotres").addClass('cambiacolorHombre');
                    }
                }

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-success').addClass('btn-default');
                    $item.addClass('btn-success');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            });

            allPrevBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-success').trigger('click');
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
        <div class="text-center">
            <div v-if="mensajes_" class="col-12 mensajes_error">
                <div class="fas fa-times cerrarbtn" @click="cerrarMensaje">X</div>
                <div v-html="mensaje"></div>
                <a href="#" @click="obtenercoins" class="col-6"><img src="{{asset('images/2021/obtener_coins.png')}}" class="col-8"></a>
            </div>
            <img v-if="!obtenercoinsdiv && !pagadodiv" src="{{asset('images/2021/actualizar_plan.png')}}" class="col-8 offset-2">
            <img class="col-12" v-if="obtenercoinsdiv" src="{{asset('images/2021/moneda_mensaje.png')}}">
            <div v-if="monto==0 && !pagadodiv" class="card tarjeta_precios">
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
                            <td class="col-5 text-left"><img src="{{asset('images/2021/a.png')}}" style="width: 20px;"> 1000 Acton coins</td>
                            <td class="col-4"><button class="col-12 btncoins" @click="pagacoins(1000)">$1000 mxn</button></td>
                        </tr>
                        </tbody>
                    </table>
                    <a v-if="!obtenercoinsdiv" id="btnCOINS" href="#" @click="obtenercoins" class="col-12 mt-3"><img src="{{asset('images/2021/obtener_coins.png')}}" class="col-8 text-center"></a>
            </div>
        </div>
            <div v-if="pagadodiv" class="col-12 text-center" style="color: white;"><h2>Tu pago ha sido exitoso</h2></div>
            <div v-if="pagadodiv" class="card tarjeta_precios">
                <div class="card-body">
                    <h2 class="col-12 text-center" style="color: #0D47A1">¡Elige la fecha de inicio de tu nuevo reto!</h2>
                    <div class="col-8 offset-2 fechas_inicio" id="fecha1" @click="setfecha(fecha1)">@{{ fecha1 }}</div>
                    <div class="col-8 offset-2 fechas_inicio" id="fecha2" @click="setfecha(fecha2)">@{{ fecha2 }}</div>
                    <div class="col-8 offset-2 fechas_inicio" id="fecha3" @click="setfecha(fecha3)">@{{ fecha3 }}</div>
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
                    intereses: ['Deportes','Cine','Espiritualidad','Bailar','Viajar','Música','Leer','Gastronomía','Animales','Idiomas','Astrología','Cantar','Futbol','Yoga','Arte','Politica','Negocios'],
                    genero: ['Hombre', 'Mujer'],
                    genero_2: ['Hetero', 'Gay', 'Bi', 'Trans'],
                    situacion: ['Casado(a)', 'Soltero(a)', 'Divorciado(a)','Viudo(a)','Union Libre', 'Abierto a conocer a alguien'],
                    idiomas: ['Español', 'Ingles', 'Aleman', 'Japones', 'Chino', 'Portugues'],
                    gym_estado: [],
                    gym_ciudad: [],
                    gyms: [],
                    sexo_etapa: 'HombreEtapa',
                    obtenercoinsdiv: false,
                    pagadodiv: false,
                    mensajes_: false,
                    coins: 0,
                    monto: 0,
                    fecha1: '',
                    fecha2: '',
                    fecha3: '',
                    ffecha1: '',
                    ffecha2: '',
                    ffecha3: '',
                }
            },
            methods: {
                //cargarFoto: function (event) {
                cargarFoto: function () {
                    let imagen = null;
                    /*if (event.dataTransfer == undefined){
                        imagen = event.target.files[0];
                    }else{
                        imagen = event.dataTransfer.files[0];
                    }*/
                    let vm = this;
                    let fm = new FormData();
                    vm.loadingFoto = true;
                    vm.errors = [];
                    fm.append('id', this.user.id);
                    fm.append('imagen', vm.resultURL);
                    //fm.append('imagen', imagen);
                    axios.post('{{url('cuenta/subirFoto')}}', fm).then(function (response) {
                        vm.loadingFoto = false;
                        if (response.data.status == 'ok'){
                            Vue.nextTick(function () {
                                vm.fotografia = response.data.imagen;
                            });
                        }
                    }).catch(function (error) {
                        vm.loadingFoto = false;
                        console.log(error.response.data.errors)
                        vm.errors = error.response.data.errors;
                    });
                },
                guardarLugar: function(){
                    const canvas = this.$refs.clipper.clip();//call component's clip method
                    if(canvas !== ''){
                        this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image
                        this.cargarFoto();
                    }
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                            usuario: this.user,
                        }
                        ).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                        this.guardado = true;
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                guardarInfoGeneral: function(){
                    /*axios.post('{{url('/usuarios/guardaInfoGeneral')}}',*/
                    this.finalizar = true;
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                            usuario: this.user,
                            edad: this.user.edad,
                            gym: this.user.gym,
                            intereses: this.user.intereses,
                            empleo: this.user.empleo,
                            estudios: this.user.estudios,
                            idiomas: this.user.idiomas,
                            edad_publico: this.user.edad_publico,
                            estudios_publico: this.user.estudios_publico,
                            gym_publico: this.user.gym_publico,
                            intereses_publico: this.user.intereses_publico,
                            empleo_publico: this.user.empleo_publico,
                            idiomas_publico: this.user.idiomas_publico,
                        }
                        ).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                        this.guardado = true;
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                guardarInfoGeneralFin: function(){
                    /*axios.post('{{url('/usuarios/guardaInfoGeneral')}}',*/
                    this.finalizar = true;
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                            usuario: this.user,
                            edad: this.user.edad,
                            gym: this.user.gym,
                            intereses: this.user.intereses,
                            empleo: this.user.empleo,
                            estudios: this.user.estudios,
                            idiomas: this.user.idiomas,
                            edad_publico: this.user.edad_publico,
                            estudios_publico: this.user.estudios_publico,
                            gym_publico: this.user.gym_publico,
                            intereses_publico: this.user.intereses_publico,
                            empleo_publico: this.user.empleo_publico,
                            idiomas_publico: this.user.idiomas_publico,
                        }
                        ).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                        this.guardado = true;
                        window.location.href = '/home';
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getGYM: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getGYM')}}').then((response) => {
                        vm.gyms=[];
                        for(var e in response.data){
                            vm.gyms.push(response.data[e].gym);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getEstadosGYM: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getEstadosGYM')}}').then((response) => {
                        vm.gym_estado=[];
                        //this.estados.push(response.data);
                        for(var e in response.data){
                            vm.gym_estado.push(response.data[e].estado);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCiudadesGYM: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCiudadesGYM')}}', {estado:this.gym_estado}).then((response) => {
                        this.gym_ciudad=[];
                        for(var e in response.data){
                            vm.gym_ciudad.push(response.data[e].ciudad);
                        }
                    }).catch(function (error) {
                        vm.errors = error.response;
                    });
                },
                getEstados: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getEstados')}}').then((response) => {
                        this.estados=[];
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        //this.estados.push(response.data);
                        console.log(response.data);
                        for(var e in response.data){
                            this.estados.push(response.data[e].estado);
                            console.log(e);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCiudades: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCiudades')}}', {estado:this.filtros.estado}).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        //this.ciudades.push(response.data);
                        for(var e in response.data){
                            this.ciudades.push(response.data[e].ciudad);
                            console.log(e);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCPs: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCP')}}', {ciudad:this.filtros.ciudad}).then((response) => {
                        this.cps=[];
                        this.colonias=[];
                        //this.cps.push(response.data);
                        for(var e in response.data){
                            this.cps.push(response.data[e].cp);
                            console.log(e);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getColonias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getColonias')}}', {cp:this.filtros.cp}).then((response) => {
                        this.colonias=[];
                        //this.colonias.push(response.data);
                        for(var e in response.data){
                            this.colonias.push(response.data[e].colonia);
                            console.log(e);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                save: function () {
                    let vm = this;
                    vm.loading = true;
                    vm.errors = [];
                    axios.post('{{url('cuenta')}}', this.user).then(function (response) {
                        vm.loading = false;
                        if (response.data.status == 'ok'){
                            vm.mensaje = '<div class="text-success text-center"><i class="fas fa-check-circle"></i> Guardado correctamente.</div>';
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                },
                upload: function(e){
                    if (e.target.files.length !== 0) {
                        if(this.imgURL) URL.revokeObjectURL(this.imgURL)
                        this.imgURL = window.URL.createObjectURL(e.target.files[0]);
                    }
                },
                getResult: function () {

                    const canvas = this.$refs.clipper.clip();//call component's clip method
                    this.resultURL = canvas.toDataURL("image/jpeg", 1);//canvas->image
                    this.cargarFoto();
                },
                addTag (newTag) {
                    var vm = this;
                    vm.user.gym = newTag;
                    axios.post('{{url('cuenta/agregarGYM')}}', {gym:vm.user.gym}).then(function (response) {
                        this.getGYM();
                    }).catch(function (error) {
                    });
                },
                agregar: function (semanas) {
                    let vm = this;
                    vm.errors = [];
                    axios.post('/cuenta/agregarsemanas/', {semanas: semanas}).then(function (response) {
                        if (response.data.status == 'ok'){
                            vm.pagadodiv = true;
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
                    vm.cerrarMensaje = false;
                },
                cerrarMensaje: function () {
                    let vm = this;
                    vm.mensajes_ = false;
                },
                setfecha: function (fecha) {
                    let vm = this;
                    axios.post('/cuenta/setDia/', {semanas: semanas}).then(function (response) {
                        if (response.data.status == 'ok'){
                            window.location = '/home';
                        }else{
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                },

            },
            mounted: function () {
                this.getEstados();
                this.getEstadosGYM();
                this.getGYM();
            },
            created: function () {
                var vm = this;
                this.user = this.p_user;
                this.user.codigo_nuevo = this.user.codigo_nuevo;
                this.filtros.estado = this.user.estado;
                if(vm.user.idiomas == ''){
                    vm.user.idiomas = null;
                }
                if(vm.user.intereses == ''){
                    vm.user.intereses = null;
                }
                if (vm.user.genero == 1){
                    vm.sexo = 'Mujer';
                    vm.sexo_etapa = 'MujerEtapa';
                    vm.user.genero = 'Mujer';
                }else{
                    vm.sexo = 'Hombre';
                    vm.sexo_etapa = 'HombreEtapa';
                    vm.user.genero = 'Hombre';
                }

            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
