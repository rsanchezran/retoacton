@extends('layouts.app')
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
    </style>
    @endsection
@section('content')
    <div id="vue">
        <div class="container">
            <cuenta :p_user="{{$user}}"></cuenta>
        </div>
    </div>
    <template id="cuenta-template">
        <div class="card">
            <div class="card-header"><i class="far fa-user"></i> Mi cuenta</div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <div class="col-sm-4 col-12 text-center" style="border: 2px dashed grey; padding: 5px;" @drop.prevent="cargarFoto($event)" @dragover.prevent>
                        <div>
                            <i v-if="loadingFoto" class="fa fa-spinner fa-spin"></i>
                            <img v-else id="fotografia" :src="fotografia"
                                 width="200">
                        </div>
                        <div>
                            <label for="foto" :class="loading?'disabled':''" class="custom-file-upload">
                                <i class="fa fa-cloud-upload"></i> Sube tu foto
                            </label>
                            <br>
                            <span>O arrastra el archivo desde tu computadora</span>
                            <input id="foto" type="file" @change="cargarFoto($event)" :disabled="loading">
                            <form-error name="imagen" :errors="errors"></form-error>
                        </div>
                    </div>
                    <div class="col-sm-8 col-12">
                        <div>
                            <label>Correo</label>
                            <label class="form-control disabled">@{{ user.email }}</label>
                            <br>
                        </div>
                        <div>
                            <label class="required">Contraseña</label>
                            <input type="password" class="form-control" v-model="user.pass_confirmation">
                            <form-error name="pass_confirmation" :errors="errors"></form-error>
                        </div>
                        <div>
                            <label class="required">Confirmar contraseña</label>
                            <input type="password" class="form-control" v-model="user.pass">
                            <form-error name="pass" :errors="errors"></form-error>
                        </div>
                        <div>
                            <!--label>Tarjeta para depositar comisiones <span class="small">(por favor verifica que sea correcta)</span></label>
                            <input :class="'form-control '+(user.tarjeta==null?'required':'not')" v-model="user.tarjeta" maxlength="16">
                            <span class="float-right small">@{{ user.tarjeta.length }}/16</span>
                            <form-error name="tarjeta" :errors="errors"></form-error-->
                        </div>
                        <div>
                            <br>
                            <button class="btn btn-success" :disabled="loading" @click="save()">
                                <i v-if="loading" class="far fa-spinner fa-spin"></i>
                                <i v-else class="far fa-save"></i>
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
<br>

                <div class="row col-md-12">

                    <div class="col-sm-3">
                        <label>Estado</label>
                        <select class="form-control" v-model="filtros.estado" @keyup.enter="buscar" @change="getCiudades()">
                            <option></option>
                            <option v-for="p in this.estados[0]">@{{ p.estado }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Ciudad</label>
                        <select class="form-control" v-model="filtros.ciudad" @keyup.enter="buscar" @change="getCPs()">
                            <option></option>
                            <option v-for="p in this.ciudades[0]">@{{ p.ciudad }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Codigo Postal</label>
                        <select class="form-control" v-model="filtros.cp" @keyup.enter="buscar" @change="getColonias()">
                            <option></option>
                            <option v-for="p in this.cps[0]">@{{ p.cp }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Colonias</label>
                        <select class="form-control" v-model="filtros.colonia" @keyup.enter="buscar">
                            <option></option>
                            <option  v-for="p in this.colonias[0]">@{{ p.colonia }}</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label v-if="guardado">Ubicacion actualizada</label>
                        <br>
                        <button class="btn btn-light" @click="guardarLugar">
                            <i class="fas fa-search"></i>&nbsp;Guardar
                        </button>
                    </div>
                </div>

            </div>
    </template>
@endsection
@section('scripts')
    <script>
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
                        ingresadosReto: ''
                    }
                }
            },
            methods: {
                cargarFoto: function (event) {
                    let imagen = null;
                    if (event.dataTransfer == undefined){
                        imagen = event.target.files[0];
                    }else{
                        imagen = event.dataTransfer.files[0];
                    }
                    let vm = this;
                    let fm = new FormData();
                    vm.loadingFoto = true;
                    vm.errors = [];
                    fm.append('id', this.user.id);
                    fm.append('imagen', imagen);
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
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
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
                getEstados: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getEstados')}}').then((response) => {
                        this.estados=[];
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.estados.push(response.data);
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
                        this.ciudades.push(response.data);
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
                        this.cps.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getColonias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getColonias')}}', {cp:this.filtros.cp}).then((response) => {
                        this.colonias=[];
                        this.colonias.push(response.data);
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
                            window.location.href = response.data.redirect;
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                }
            },
            mounted: function () {
                this.getEstados();
            },
            created: function () {
                this.user = this.p_user;
                this.user.pass_confirmation = this.user.pass;
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
