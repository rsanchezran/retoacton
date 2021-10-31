@extends('layouts.app_datos')
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
        }

        .Hombre { /* Microsoft Edge */
            color: #0080DD !important;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
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
        .card{
            background: white !important;
            padding: 10px;
            border: 1px solid #cccccc;
            font-size: 12px !important;
        }
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">

    @endsection
@section('content')
    <div id="vue">
        <div class="container">
            <cuenta :estado_cuenta="{{$estado_cuenta}}" :suma="{{$suma}}"></cuenta>
        </div>
    </div>
    <template id="cuenta-template">

        <div class="col-12 text-center">

            <div class="col-10 offset-1 text-center">
                    <img src="{{asset('images/2021/titulo_estado.png')}}" class="w-100">
            </div>

            <div class="col-8 offset-2 text-center">
                <h2>@{{ sumatoria }}</h2>
            </div>

            <div class="col-6 offset-3 text-center">
                <img src="{{asset('images/2021/acton_coins.png')}}" class="w-100">
            </div>

            <div class="card mt-3">
                <div class="card-body">

                    <table class="table-striped" id="tblCuentas">
                        <thead>
                        <tr>
                            <td>ACTON COINS</td>
                            <td>PROVIENE DE</td>
                            <td>GASTOS DE OPERACIÓN</td>
                            <td>TRASNFIERE A PESOS</td>
                            <td>ESTATUS</td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="e in estado_cuenta">
                                <td>@{{ e.monto }}</td>
                                <td>@{{ e.tipo_compra }}</td>
                                <td>@{{ e.monto }}</td>
                                <td>100</td>
                                <td v-if="pagado">SI</td>
                                <td v-else>NO</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="sumatoria >= 2000" class="col-8 offset-2 text-center mt-5">
                <img src="{{asset('images/2021/transferir_coins.png')}}" class="w-100" @click="cobrar">
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
            props: ['estado_cuenta', 'suma'],
            data: function () {
                return {
                    user: {},
                    errors: [],
                    estados_cuentas: [],
                    sumatoria: [],
                    loading: false,
                    loadingFoto: false,
                }
            },
            methods: {
                cobrar: function(){
                    var vm = this;
                    axios.post('{{url('cuenta/cobrar/')}}'
                    ).then(function (response) {
                        window.location.reload();
                    });
                }
            },
            mounted: function () {
            },
            created: function () {
                this.estados_cuentas = this.estado_cuenta;
                this.sumatoria = this.suma;

            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
