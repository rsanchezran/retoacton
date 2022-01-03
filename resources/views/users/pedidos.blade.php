@extends('layouts.app_suplementos')
@section('header')
    <style>
        .card, .card-body {
            background: white !important;
            border: 1px solid #999;
            padding: 20px;
        }

        @media only screen and (max-width: 800px) {

        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="">
            <temp-retos :usuarios="{{$usuarios}}"></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="w-100 row" style="margin-top: 35% !important;margin-left: 0%; position: relative">
            <div class="card w-100">
                <table class="table-striped table">
                    <thead>
                    <tr>
                        <td>Usuario</td>
                        <td>Ver info</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="i in usuarios">
                        <td>@{{ i.name }} @{{ i.last_name  }}</td>
                        <td @click="verDetalle(i.id, i.enviado)"><i class="fas fa-info-circle text-info"></i></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="mostrarInfo" class="card w-100">
                <table class="table-striped table">
                    <thead>
                    <tr>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>Costo</td>
                        <td>Enviado</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="i in carritos">
                        <td>@{{ i.producto }}</td>
                        <td>@{{ i.cantidad }}</td>
                        <td>@{{ i.precio }}</td>
                        <td>
                            <i v-if="i.enviado == 1" class="fas fa-check-circle text-success"></i>
                            <i v-else class="fas fa-exclamation-circle text-danger"></i>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div>
                    Direccion: @{{ usuario.estado }}, @{{ usuario.ciudad }}, @{{ usuario.colonia }}, @{{ usuario.cp }}, @{{ usuario.calle }} @{{ usuario.numero }}
                </div>
                <button class="btn btn-success" @click="enviar">Marcar Como Enviado</button>
            </div>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-retos', {
            template: '#temp',
            props: ['usuarios'],
            data: function () {
                return {
                    id: 0,
                    enviado: 0,
                    aut: 0,
                    mensaje: '',
                    scrollr: true,
                    carritos: [],
                    mostrarInfo: false,
                    usuario: [],
                }
            },
            methods: {
                verDetalle: function(id, enviado){
                    var vm = this;
                    vm.id = id;
                    vm.enviado = enviado;
                    axios.post('{{url('usuarios/pedidos-detalle/')}}', {'id': id}
                    ).then(function (response) {
                        vm.carritos = response.data;
                        vm.mostrarInfo = true;
                        axios.post('{{url('usuarios/info-pedido/')}}', {'id': id}
                        ).then(function (res) {
                            console.log(res.data);
                            vm.usuario = res.data;
                        });
                    });
                },
                enviar: function () {
                    var vm = this;
                    axios.post('{{url('usuarios/enviar-carrito/')}}', {'id': vm.id}
                    ).then(function (response) {
                        alert('Enviado');
                    });
                }
            },
            mounted: function () {
                this.aut = '{{ Auth::user()->id }}';
                console.log(this.usuarios);
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
