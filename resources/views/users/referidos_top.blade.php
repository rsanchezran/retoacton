@extends('layouts.app_interiores_ref')
@section('header')
    <style>
        .usuario{
            padding: 2px;
            margin: 5px;
            padding-top: 3px;
        }

        .settings a, .settings button{
            margin-left: 5px;
        }

        .inactivo{
            background-color: lightgray;
        }


        @media only screen and (max-width: 414px) {
            .usuario {
                padding: 3px !important;
                margin: 8px !important;
                padding-top: 3px !important;
            }
            #lstUsuarios {
                margin-top: 22% !important;
                margin-left: 46px !important;
            }
        }


        @media only screen and (max-width: 380px) {
            .usuario {
                padding: 2px;
                margin: 5px;
                padding-top: 3px;
            }
            #lstUsuarios {
                margin-top: 24% !important;
                margin-left: 40px !important;
            }
        }
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div>
            <div class="card mb-3" style="margin-top: 40%">

            </div>

            <div class="card mb-3">
                <div class="card-body"  id="lstUsuarios" style="margin-top: 24%; margin-left: 40px">
                    <div v-for="usuario in usuarios.data" class="usuario" style="">
                        <div class="row ">
                            <span class="col-12">
                                <a :href="'/cuenta/'+usuario.id">
                                    <img :src="'/cuenta/getFotografia/'+usuario.id+'/343234'"
                                         style="
                                        height: 100px;
                                        min-height: 30px;
                                        height: 30px;
                                        width: 30px;
                                        border-radius: 30px;">
                                    <strong>@{{ usuario.name+' '+usuario.last_name }}</strong></a>
                            </span>
                        </div>
                    </div>
                    <div v-if="usuarios.length == 0 || usuarios.data.length == 0" align="center">
                        <h6 colspan="6">[No hay datos para mostrar]</h6>
                    </div>
                    <div class="float-right">
                        <paginador ref="paginador" :url="'{{url('/usuarios/buscar-referidos-top')}}'" @loaded="loaded"></paginador>
                    </div>
                </div>
            </div>

        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('vue-multiselect', window.VueMultiselect.default);

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
                        ciudad: '0',
                        cp: '0',
                        estado: '0',
                        colonia: '0',
                        tiendagym: '0',
                        conexion: '0',
                        ingresadosReto: '',
                        codigo_personal: '',
                        intereses: [],
                        orientacion: '',
                        sexo: '',
                        edad_inicio: '',
                        edad_fin: '',
                        estatus: [],
                        idiomas: [],
                    },
                    usuario: {
                        id: '',
                        nombre: '',
                        tarjeta: '',
                        saldo: '',
                        referencia: '',
                        dias_reto:''
                    },
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


            },
            mounted: function () {
                var vm = this;
                this.buscar();
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
