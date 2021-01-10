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
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <input class="form-control" placeholder="Nombres" v-model="informacion.nombres">
                        <form-error name="nombres" :errors="errors"></form-error>
                        <input class="form-control" placeholder="Apellidos" v-model="informacion.apellidos">
                        <form-error name="apellidos" :errors="errors"></form-error>
                        <input class="form-control" placeholder="Teléfono" v-model="informacion.telefono">
                        <form-error name="telefono" :errors="errors"></form-error>
                        <input type="email" class="form-control" placeholder="Correo electrónico" v-model="informacion.email"
                               @blur="saveContactoTienda" @keypress.enter="saveContactoTienda">
                        <form-error name="email" :errors="errors"></form-error>
                        <div>@{{mensaje}}</div>
                        <div class="mt-4 text-left">
                            <button class="btn btn-primary acton" @click="saveContactoTienda" :disabled="loading">
                                Continuar
                                <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fa fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <table class="table">
                    <tr class="table-header">
                        <th></th>
                        <th>Contacto</th>
                        <th>Correo electrónico</th>
                        <th>Saldo</th>
                        <th>Pagado</th>
                        <th>Fecha de registro</th>
                        <th></th>
                    </tr>
                    @forelse ($users as $u)
                        <tr>
                            <th>
                                <i v-if="contacto.contacto" class="fa fa-user text-info"></i>
                                <i v-else class="fa fa-user text-default"></i>
                            </th>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->saldo }}</td>
                            <td>{{ $u->ref_tienda_pagado }}</td>
                            <td class="text-center">{{ $u->created_at }}</td>
                            <td>
                                <button v-tooltip="{content:'Pagar'}" class="btn btn-sm btn-success" @click="PagarTienda({{$u->id}})">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                                <button v-tooltip="{content:'Eliminar usuario'}" class="btn btn-sm btn-danger" @click="confirmar(usuario)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr v-if="contactos.data.length==0">
                            <td colspan="7">[No hay datos que mostrar]</td>
                        </tr>
                    @endforelse
                </table>
            </div>
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
                    errors: [],
                    buscando:false,
                    filtros:{
                        email:'',
                        nombres:'',
                        medio:''
                    },
                    contactos:{
                        data:[]
                    },
                    loading: false,
                    informacion: {
                        nombres: '',
                        apellidos: '',
                        email: '',
                        telefono: '',
                        medio: '',
                        tipo: ''
                    },
                    mensaje:'',
                    contacto:{},
                    sent: false,
                    srcVideo: '',
                    features: {
                        comidas: true,
                        entrenamiento: true,
                        suplementos: true,
                        videos: true
                    },
                    encontrado: null,
                    referencia: '',
                    original: '0',
                    monto: '0',
                    descuento: '0',
                    mensaje: ''
                }

            },
            methods: {
                terminado: function () {
                    window.location.href = "{{url('/login')}}";
                },
                buscarReferencia: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    axios.get('{{url('buscarReferencia')}}/' + vm.informacion.codigo).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        vm.encontrado = true;
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.sent){
                            vm.saveContacto();
                        }
                        vm.loading = false;
                        vm.encontrado = false;
                    });
                },
                PagarTienda: function (usuario) {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    axios.get('{{url('configuracion/pagar-tienda')}}/' + usuario).then(function (response) {
                        if(response.data.status=='OK'){
                            window.location.reload()
                        }
                    }).catch(function () {
                        vm.loading = false;
                    });
                },
                saveContactoTienda: function () {
                    let vm = this;
                    this.loading = true;
                    this.errors = {};
                    this.informacion.nombres = this.informacion.nombres.trim();
                    this.informacion.apellidos = this.informacion.apellidos.trim();
                    this.informacion.email = this.informacion.email.trim();
                    this.informacion.telefono = this.informacion.telefono.trim();
                    this.informacion.codigo = 2;
                    if(this.informacion.nombres==''){
                        this.errors.nombres = ['El nombre es obligatorio'];
                    }
                    if(this.informacion.apellidos==''){
                        this.errors.apellidos = ['Los apellidos son obligatorios'];
                    }
                    if (this.informacion.telefono==''){
                        this.errors.telefono = ['El teléfono es obligatorio'];
                    }
                    if (this.informacion.email==''){
                        this.errors.email = ['El correo electrónico es obligatorio'];
                    }
                    if (Object.keys(this.errors).length == 0) {
                        axios.post("{{url("configuracion/saveContactoTienda")}}", this.informacion).then(function (response) {
                            vm.sent = true;
                            vm.loading = false;

                            vm.mensaje = response.data.mensaje;
                            if(response.data.mensaje==''){
                                window.location.reload()
                            }
                        }).catch(function (error) {
                            vm.sent = false;
                            vm.loading = false;
                            vm.errors = error.response.data.errors;
                        });
                    }else{
                        this.sent = false;
                        this.loading = false;
                    }
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
