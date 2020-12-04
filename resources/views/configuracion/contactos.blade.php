@extends('layouts.app')
@section('header')
    <style>
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <contactos :medios="{{$medios}}"></contactos>
        </div>
    </div>
    <template id="contactos-template">
        <div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <span><i class="far fa-users"></i> Listado de contactos</span>
                    <button class="btn btn-sm btn-light" style="color: black" @click="enviarCorreos">Enviar correos</button>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="col-sm-3">
                            <label>Contacto</label>
                            <input type="text" class="form-control" v-model="filtros.nombres" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-3">
                            <label>Correo</label>
                            <input type="text" class="form-control" v-model="filtros.email" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-3">
                            <label>Medio</label>
                            <select class="selectpicker form-control" v-model="filtros.medio" @change="buscar">
                                <option value="">[Todos]</option>
                                <option v-for="medio in medios" :value="medio">@{{medio}}</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <br>
                            <button class="btn btn-light" :disabled="buscando" @click="buscar">
                                <i class="fa fa-search" v-if="!buscando"></i>
                                <i class="fa fa-spinner fa-spin" v-if="buscando"></i> Buscar
                            </button>
                            <button class="btn btn-light" @click="exportar" :disabled=" buscando">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-file-excel"></i>&nbsp;Exportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr class="table-header">
                            <th></th>
                            <th>Contacto</th>
                            <th>Correo electrónico</th>
                            <th>Teléfono</th>
                            <th>Fecha de registro</th>
                            <th>Etapa de interés</th>
                            <th>Medio</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr v-for="contacto in contactos.data">
                            <th>
                                <i v-if="contacto.contacto" class="fa fa-user text-info"></i>
                                <i v-else class="fa fa-user text-default"></i>
                            </th>
                            <td>@{{ contacto.nombres +' '+contacto.apellidos }}</td>
                            <td>@{{ contacto.email }}</td>
                            <td>@{{ contacto.telefono }}</td>
                            <td class="text-center"><fecha :fecha="contacto.created_at"></fecha></td>
                            <td class="text-center">@{{ contacto.etapa}}</td>
                            <td>@{{ contacto.medio}}</td>
                            <td>
                                <button v-if="contacto.medio=='Contacto'" class="btn btn-sm btn-default" @click="getMensaje(contacto)" :disabled="buscando">
                                    <i class="fa fa-envelope" v-if="!buscando"></i>
                                    <i class="fa fa-spinner fa-spin" v-if="buscando"></i> Ver mensaje
                                </button>
                            </td>
                            <td>
                                <button v-tooltip="{content:'Eliminar contacto'}" class="btn btn-sm btn-danger" @click="confirmarContacto(contacto)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="contactos.data.length==0">
                            <td colspan="7">[No hay datos que mostrar]</td>
                        </tr>
                    </table>
                    <paginador ref="paginador" :url="'{{url('configuracion/contactos/buscar')}}'" @loaded="loaded"></paginador>
                </div>
            </div>
            <modal ref="mensajeModal" title="Mensaje del contacto" :showok="false">
                @{{ mensaje }}
            </modal>
            <modal ref="eliminarModal" title="Eliminar contacto" @ok="quitarContacto">
                <span>¿Estás seguro de querer eliminar al contacto?</span>
                <br>
                <span><b>Nombre : </b>@{{ contacto.nombres+' '+contacto.apellidos }}</span>
                <br>
                <span><b>Email : </b>@{{ contacto.email }}</span>
            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('contactos', {
            template: '#contactos-template',
            props:['medios'],
            data:function () {
                return {
                    buscando:false,
                    filtros:{
                        email:'',
                        nombres:'',
                        medio:''
                    },
                    contactos:{
                        data:[]
                    },
                    mensaje:'',
                    contacto:{}
                }
            },
            methods: {
                loaded: function (contactos) {
                    this.contactos = contactos;
                    this.buscando = false;
                },
                buscar: function () {
                    this.buscando= true;
                    this.$refs.paginador.consultar(this.filtros);
                },
                getMensaje: function (contacto) {
                    let vm = this;
                    vm.buscando = true;
                    axios.get('{{url('configuracion/contactos/getMensaje/')}}/'+contacto.id).then(function (response) {
                        vm.mensaje = response.data;
                        vm.buscando = false;
                        vm.$refs.mensajeModal.showModal();
                    }).catch(function () {
                        vm.buscando = false;
                    });
                },
                confirmarContacto: function (contacto) {
                    this.contacto = contacto;
                    this.$refs.eliminarModal.showModal();
                },
                quitarContacto: function (id) {
                    let vm = this;
                    axios.post('{{url('configuracion/contactos/quitar')}}', this.contacto).then(function (response) {
                        vm.$refs.eliminarModal.closeModal();
                        vm.buscar();
                    });
                },
                exportar: function () {
                    window.open('{{url('/configuracion/contactos/exportar')}}/'+JSON.stringify(this.filtros),'_blank');
                },
                enviarCorreos: function () {
                    let vm = this;
                    axios.post('{{url('configuracion/contactos/enviarCorreos')}}').then(function (response) {
                    });
                }
            },
            mounted:function () {
                this.buscar();
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
