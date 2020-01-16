@extends('layouts.app')
@section('header')
    <style>
        #imagenes img {
            cursor: pointer;
        }

        #informacionModal .modal-content{
            min-width: 700px;
        }

        input[type="file"]{
            width: 120px;
        }

        #fotoModal .modal-content{
            background: none;
            text-align: center;
        }

        #fotoModal .modal-header{
            border-bottom: 0;
        }

        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        audio{
            width: 100%;
        }
    </style>
@endsection
@section('content')

<div id="vue" >
    <div class="container">
        <temp-reto :rol="'{{$rol}}'" :datos_reto="{{$datos_reto}}" ></temp-reto>
    </div>
</div>

<template id="template">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span><i class="far fa-camera"></i> Fotos</span>
            <a class="btn btn-sm btn-light" v-if="rol=='cliente'" href="{{url('reto/ejemplo')}}" target="_blank">
                <i class="fa fa-camera"></i> Ver ejemplo del reto
            </a>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap" id="imagenes">
                <div v-for="(dato, index) in datos"  class="card" style="width: 12rem">
                    <div class="card-header d-flex justify-content-between" style="padding: 5px 10px;">
                        <span>Día @{{ dato.dia }}</span>
                        <button class="btn btn-sm btn-default" @click="mostrarModal(dato)">
                            <i class="fas fa-comment-lines"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div v-if="dato.subir" align="center">
                            <div>
                                <label :for="'file'+index" class="custom-file-upload">
                                    <i class="fa fa-cloud-upload"></i> Subir foto
                                </label>
                                <input :id="'file'+index" type="file" accept="image/png,image/jpg,image/jpeg"
                                       @change="agregarImagen(index, $event)" :disabled="loading">
                            </div>
                            <div>
                                <label :for="'audio'+index" class="custom-file-upload">
                                    <i class="fa fa-cloud-upload"></i> Subir audio
                                </label>
                                <input :id="'audio'+index" type="file" accept="audio/mp3"
                                       @change="agregarAudio(index, $event)" :disabled="loading">
                            </div>
                            <form-error :name="'imagen'+index" :errors="errors"></form-error>
                            <form-error :name="'audio'+index" :errors="errors"></form-error>
                        </div>
                        <div align="center">
                            <span v-if="dato.loading">Estamos procesando el archivo, espera un momento porfavor...</span>
                            <i v-if="dato.loading" class="fa fa-spinner fa-spin"></i>
                            <img v-if="dato.imagen!='' && !dato.loading" :id="'img'+index" :src="dato.imagen" width="160"
                                 @click="mostrarImagen(dato.imagen)"/>
                            <br>
                            <br>
                            <audio controls v-if="dato.audio!=''"><source :src="dato.audio" /></audio>
                        </div>
                    </div>
                    <div v-if="dato.comentario" class="card-footer text-muted">
                        <span>Comentario: @{{ dato.comentario }}</span>
                    </div>
                </div>
            </div>
        </div>
        <modal id="informacionModal" ref="informacionModal" title="Recomendaciones del día" @ok="anotar()" :showok="rol=='admin'">
            <br>
            <div>
                <wysiwyg v-model="dia.comentarios"/>
            </div>
        </modal>
        <modal id="fotoModal" ref="fotoModal" :showfooter="false" :btncerrar="true" title="Foto">
            <div style="padding-top: 15px;">
                <img :src="imagen" style="margin: auto; display: block">
            </div>
        </modal>
    </div>
</template>

@endsection
@section('scripts')
    <script>
        Vue.component('temp-reto',{
           template:'#template',
            props: ['rol','datos_reto'],
            data: function () {
                return {
                    loading : false,
                    datos: this.datos_reto,
                    imagen:'',
                    errors:[],
                    dia:{
                        comentarios:''
                    }
                }
            },
            methods: {
                agregarImagen: function (index, event) {
                    let vm = this;
                    let fm = new FormData();
                    let file = event.target.files[0];
                    let dato = this.datos[index];
                    vm.loading = true;
                    dato.loading = true;
                    fm.append("imagen", file);
                    fm.append("dia", index+1);
                    vm.errors = [];
                    vm.datos[index].error = false;
                    axios.post("{{url('/reto/saveImagen')}}", fm).then(function (response) {
                        vm.loading = false;
                        dato.loading = false;
                        Vue.nextTick(function () {
                            dato.imagen = response.data.imagen;
                        });
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                        dato.loading = false;
                    });
                },
                agregarAudio:function(index, event){
                    let vm = this;
                    let fm = new FormData();
                    let file = event.target.files[0];
                    let dato = this.datos[index];
                    vm.loading = true;
                    dato.loading = true;
                    fm.append("audio", file);
                    fm.append("dia", index+1);
                    vm.errors = [];
                    vm.datos[index].error = false;
                    axios.post("{{url('/reto/saveAudio')}}", fm).then(function (response) {
                        dato.loading = false;
                        vm.loading = false;
                        Vue.nextTick(function () {
                            dato.audio = response.data.audio;
                        });
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        dato.loading = false;
                        vm.loading = false;
                    });
                },
                mostrarModal: function (dia) {
                    this.dia = dia;
                    this.$refs.informacionModal.showModal();
                },
                mostrarImagen: function (imagen) {
                    this.imagen = imagen;
                    this.$refs.fotoModal.showModal();
                },
                anotar: function () {
                    if (this.rol!='cliente'){
                        let vm = this;
                        axios.post("{{url('/reto/anotar')}}",{dia: this.dia.dia, comentarios: this.dia.comentarios}).then(function (response) {
                            vm.$refs.informacionModal.working = false;
                            vm.$refs.informacionModal.closeModal();
                        }).catch(function (error) {
                            vm.$refs.informacionModal.working = false;
                            vm.errors = error.response.data.errors;
                        });
                    }
                }
            },
        });

        let vue = new Vue({
            el:'#vue'
        });
    </script>
@endsection
