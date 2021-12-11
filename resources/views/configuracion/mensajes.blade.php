@extends('layouts.app_interiores')
@section('header')
    <style>
        .usuario{
            padding: 5px;
            margin: 5px;
            border-bottom: 1px solid lightgray;
        }

        .settings a, .settings button{
            margin-left: 5px;
        }

        .inactivo{
            background-color: lightgray;
        }

        .sin_leer{
            width: 10px;
            height: 10px;
            background-color: red;
            display: inline-block;
            border-radius: 10px;
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div>
            <div class="row col-12">
                <div class="col-6 offset-3">
                    <img src="{{asset('images/2021/mensajes.png')}}" class="col-12 mt-4">
                </div>
                <div class="col-3">
                    <img src="{{asset('images/2021/pencil.png')}}" class="col-11 mt-4" @click="verEliminar">
                </div>
            </div>
            <br>
            <div class="col-12">
                <a :href="'mensaje-directo/1'" class="btn btn-primary col-12" style="background: #0080DD !important;">¿Tienes una duda?<br>Pregunta a tu coach</a>
            </div>
            <!--a :href="'mensaje-directo/1'" class="btn btn-success text-white">Solución de dudas</a-->
            <div v-for="usuario in usuarios.data" class="d-flex usuario mt-4">
                <div class="col-8 d-flex flex-column align-items-start">
                    <!--span v-if="usuario.vigente"-->
                    <a :href="'mensaje-directo/'+usuario.id" class=""><img :src="'/cuenta/getFotografia/'+usuario.id+'/232'"
                    width="50px" style="border-radius: 30px">      @{{ usuario.name+' '+usuario.last_name }}</a>
                    <!--/span-->
                </div>
                <div class="col-4 d-flex flex-column align-items-end">

                    <div v-if="eliminar_mensajes" @click="eliminarMensajes(usuario.id)" class="text-danger" >
                        <i class="fas fa-times mt-2"></i> <!--div v-if="usuario.sin_leer>0" class="sin_leer"></div-->
                    </div>

                    <!--a :href="'mensaje-directo/'+usuario.id" v-tooltip="{content:'Enviar mensaje'}" class="btn btn-sm btn-default" >
                        <i class="fas fa-comments"></i> <div v-if="usuario.sin_leer>0" class="sin_leer"></div>
                    </a-->
                </div>
            </div>

            <div v-if="usuarios.length == 0 || usuarios.data.length == 0" align="center">
                <h6 colspan="6">[No hay datos para mostrar]</h6>
            </div>
            <div class="float-right">
                <paginador ref="paginador" :url="'{{url('/configuracion/buscarSeguirMensajes')}}'" @loaded="loaded"></paginador>
            </div>

        </div>

    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-retos', {
            template: '#temp',
            data: function () {
                return {
                    buscando: false,
                    usuarios: [],
                    filtros: {
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: '',
                        estado: '0',
                        ingresadosReto: ''
                    },
                    usuario: {
                        id: '',
                        nombre: '',
                        tarjeta: '',
                        saldo: '',
                        referencia: '',
                        dias_reto:'',
                        saldoAumentado: 0,
                    },
                    referencias:{
                        data:[]
                    },
                    pagos:[],
                    compras:[],
                    eliminar_mensajes: false,
                }
            },
            methods: {
                loaded: function (usuarios) {
                    this.usuarios = usuarios;
                    this.buscando = false;
                },
                buscar: function () {
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
                },
                verEliminar: function(){
                    var vm = this;
                    if(vm.eliminar_mensajes) {
                        vm.eliminar_mensajes = false;
                    }else{
                        vm.eliminar_mensajes = true;
                    }
                },
                eliminarMensajes: function(id){
                    var vm = this;
                    axios.post('{{url('cuenta/mensajes-eliminar/')}}', {'id': id}
                    ).then(function (response) {
                    });
                },

            },
            mounted: function () {
                this.buscar();
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
