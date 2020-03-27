@extends('layouts.app')
@section('header')
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <pago :usuario="{{$usuario}}"></pago>
        </div>
    </div>
    <template id="pago-template">
        <div class="card">
            <div class="card-header"><i class="far fa-user"></i> Reinscripción</div>
            <div class="card-body">
                <div>
                    <p>Bienvenido nuevamente al Reto Acton, la duración del reto en tu reinscripción será de {{env("DIAS2")}},
                    antes de comenzar nos gustaría saber de tí:</p>
                </div>
                <hr>
                <div>
                    <h5 class="text-center">El costo de Reto Acton en modo reinscripción es de $<money :cantidad="'{{env("COBRO_REFRENDO")}}'"></money>
                    y estas son las formas de pago:</h5>
                    <br>
                    <cobro ref="cobro" :cobro="'{{env('COBRO_REFRENDO')}}'" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                           :sandbox="'{{env('SANDBOX')}}'==true" :llave="'{{env('OPENPAY_PUBLIC')}}'" :meses="false"
                           @terminado="terminado"></cobro>
                </div>
            </div>
        </div>
    </template>
@endsection
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    <script>

        Vue.component('pago', {
            template:"#pago-template",
            props:['usuario'],
            mounted: function () {
                this.$refs.cobro.configurar(this.usuario.name,this.usuario.last_name, this.usuario.email, this.usuario.telefono, null,'');
            },
            methods: {
                terminado: function () {
                    window.location.href = 'home';
                },
            }
        });

        var vue = new Vue({
            el:"#vue"
        });
    </script>
@endsection
