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
            <temp-retos :carrito="{{$carrito}}"></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="w-100 row" style="margin-top: 35% !important;margin-left: 0%; position: relative">
            <div class="card w-100">
                <table class="table-striped table">
                    <thead>
                    <tr>
                        <td>Producto</td>
                        <td>Cantidad</td>
                        <td>Costo</td>
                        <td>Eliminar</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="i in carrito">
                        <td>@{{ i.producto }}</td>
                        <td>@{{ i.cantidad }}</td>
                        <td>@{{ i.precio }}</td>
                        <td @click="eliminarCarrito(i.id)"><i class="fas fa-times-circle text-danger"></i></td>
                    </tr>
                    <tr>
                        <td>Envio</td>
                        <td>1</td>
                        <td>$220</td>
                    </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary" @click="pagarCarrito">Pagar</button>
            </div>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-retos', {
            template: '#temp',
            props: ['carrito'],
            data: function () {
                return {
                    id: 0,
                    aut: 0,
                    mensaje: '',
                    scrollr: true,
                    carritos: [],
                }
            },
            methods: {
                pagarCarrito: function(){
                    var vm = this;
                    axios.post('{{url('usuarios/pagar-carrito/')}}'
                    ).then(function (response) {
                        alert(response.data);
                        window.location.reload();
                    });
                },
                eliminarCarrito: function(id){
                    var vm = this;
                    axios.post('{{url('usuarios/eliminar-carrito/')}}', {'id': id}
                    ).then(function (response) {
                        //alert(response.data.mensaje);
                        window.location.reload();
                    });
                },
            },
            mounted: function () {
                this.aut = '{{ Auth::user()->id }}';
                console.log(this.carritos);
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
