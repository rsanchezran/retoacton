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
                        </div>
                        <form-error name="fotografia" :errors="errors"></form-error>
                    </div>
                    <div class="col-sm-8 col-12">
                        <div>
                            <label class="required">Correo</label>
                            <label class="form-control">@{{ user.email }}</label>
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
                            <label>Tarjeta para depositar comisiones(porfavor verifica que sea correcta)</label>
                            <input class="form-control" v-model="user.tarjeta">
                            <form-error name="tarjeta" :errors="errors"></form-error>
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
                    fotografia: '{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id).'/'.rand(0,10)}}'
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
                        vm.errors = error.response.data.errors;
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
