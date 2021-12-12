@extends('layouts.app_suplementos_ficha')
@section('header')
    <style>


        @media only screen and (max-width: 800px) {

        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="">
            <temp-retos  :tipo="{{$tipo}}"></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="w-100 row" style="margin-top: 35% !important;margin-left: 0%; position: relative">
            <a href="/usuarios/ver-carrito"><i class="fas fa-shopping-cart" style="position: absolute;color: white;font-size: 20px;margin-top: -100px;margin-left: 85%;"></i></a>
            <img :src="tipos" width="80%" style="margin-left: 11%; margin-top: -16%;">
            <img src="{{asset('images/2021/carrito.png')}}" width="70%" style="margin-left: 15%" class="mt-3" @click="carrito">
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-retos', {
            template: '#temp',
            props: ['tipo'],
            data: function () {
                return {
                    id: 0,
                    aut: 0,
                    mensaje: '',
                    scrollr: true,
                    tipos: '',
                    tipopaso: '',
                }
            },
            methods: {
                carrito: function () {
                    var vm = this;
                    axios.post('{{url('usuarios/agregar-carrito/')}}', {'tipo': vm.tipopaso}
                    ).then(function (response) {
                        alert('Producto agregado');
                    });
                }
            },
            mounted: function () {
                this.aut = '{{ Auth::user()->id }}';
                this.tipopaso = '{{$tipo}}';
                this.tipos = '/images/2021/'+this.tipopaso+'.png';
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
