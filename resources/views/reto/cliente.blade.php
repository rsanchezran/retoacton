@extends('layouts.app')
@section('header')
    <style>

        input[type="file"]{
            width: 120px;
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

        .dia{
            border: 1px solid grey;
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
            width: 12% !important;
        }

        #buscando{
            text-align: center;
        }

        .comida {
            background-color: #007FDC;
            color: #FFF;
            padding: 10px;
        }
    </style>
@endsection
@section('content')

<div id="vue" >
    <div class="container">
        <dias :dias="{{$dias}}"></dias>
    </div>
</div>

<template id="dias-template">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span><i class="far fa-camera"></i> Fotos</span>
        </div>
        <div class="card-body">
            <div>
                <div v-if="buscando" id="buscando">
                    <h5>Buscando...</h5>
                    <i class="fa fa-cog fa-spin fa-2x"></i>
                </div>
                <div align="center" v-show="!buscando">
                    <div style="border:1px dashed grey; padding: 10px;" class="col-12 col-sm-6">
                        <h1>@{{ dia.dia }}</h1>
                        <audio v-if="ejemplo.audio!=''" :src="ejemplo.audio" controls></audio>
                        <p id="comentarios"></p>
                        <img :src="ejemplo.imagen" height="200">
                    </div>
                    <hr>
                    <div>
                    <div style="border:5px dashed grey; padding: 10px;" class="col-12 col-sm-6" @drop.prevent="agregarImagen($event)" @dragover.prevent>
                            <label for="file" class="custom-file-upload">
                                <i class="fa fa-cloud-upload"></i> Sube tu foto aquí
                            </label>
                            <br>
                            <span class="small">O arrastra la imagen desde tu computadora</span>
                            <br>
                            <div>
                                <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                                <img v-else :src="dia.imagen" height="200">
                            </div>
                            <input id="file" type="file" accept="image/x-png,image/jpg,image/jpeg"
                                   @change="agregarImagen($event)">
                        </div>
                        <p>@{{ dia.comentario }}</p>
                    </div>
                    <form-error name="imagen" :errors="errors"></form-error>
                </div>
            </div>
            <h4 class="comida">Calendario</h4>
            <div class="col-12" v-for="(sem, index) in semanas">
                <h6 v-if="(index+1)<=semana" class="font-weight-bold">Semana @{{ index+1  }}</h6>
                <div v-if="(index+1)<=semana" class="d-flex flex-wrap ">
                    <div v-for="d in sem" :class="d>dias?'nodia':'dia'" @click="getDia(d)">
                        <a v-if="d<=dias" :href="'{{url('/reto/dia/')}}/'+d+'/'+genero+'/'+objetivo">
                            @{{ d }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection
@section('scripts')
    <script>
        Vue.component('dias',{
           template:'#dias-template',
            props: ['dias'],
            data: function () {
                return {
                    loading:false,
                    buscando:false,
                    imagen:'',
                    errors:{},
                    ejemplo:{
                        imagen:'',
                        comentarios:''
                    },
                    dia:{
                        dia:1,
                        imagen:'',
                        comentarios:''
                    },
                    semanas:[],
                    semana:1
                }
            },
            methods: {
                agregarImagen: function (event) {
                    let imagen = null;
                    if (event.dataTransfer == undefined){
                        imagen = event.target.files[0];
                    }else{
                        imagen = event.dataTransfer.files[0];
                    }
                    let vm = this;
                    let fm = new FormData();
                    vm.loading = true;
                    fm.append("imagen", imagen);
                    fm.append("dia", vm.dia.dia);
                    vm.errors = [];
                    axios.post("{{url('/reto/saveImagen')}}", fm).then(function (response) {
                        vm.loading = false;
                        Vue.nextTick(function () {
                            vm.dia.imagen = response.data.imagen;
                        });
                    })
                    .catch(function (error) {
                        vm.errors = error.response.data.errors;
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
                getDia: function (dia) {
                    let vm = this;
                    vm.buscando = true;
                    axios.get('{{url('/reto/getDia/')}}/'+dia).then(function (response) {
                        vm.dia = response.data.dia;
                        vm.ejemplo = response.data.ejemplo;
                        document.getElementById('comentarios').innerHTML = vm.ejemplo.comentario;
                        vm.buscando = false;
                    });
                }
            },
            created: function () {
                for(let i = 0; i < 56; i++){
                    if(i+1==this.dia){
                        this.semana = parseInt(i/7);
                    }
                    if(i % 7 == 0){
                        this.semanas.push([]);
                    }
                    this.semanas[parseInt(i/7)].push(i+1);
                }
            },
            mounted: function () {
               this.getDia(this.dias);
            }
        });

        let vue = new Vue({
            el:'#vue'
        });
    </script>
@endsection
