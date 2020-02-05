@extends('layouts.app')
@section('header')
    <style>

        input[type="file"] {
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

        .dia {
            border: 1px solid grey;
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: 0;
            font-weight: bold;
            color: #0080DD;
        }

        .nodia {
            border: 1px solid #FFF;
            text-align: center;
            margin: 5px;
            padding: 5px;
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: 0;
        }

        #buscando {
            text-align: center;
        }

        .comida {
            background-color: #007FDC;
            color: #FFF;
            padding: 10px;
        }

        .modal-content {
            width: max-content !important;
        }
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <dias :p_dias="{{$dias}}" :p_semana="{{$semana}}" :maximo="{{$maximo}}" :teoricos="{{$teoricos}}"></dias>
        </div>
    </div>

    <template id="dias-template">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <span><i class="far fa-running"></i> Actividades del Reto Acton</span>
            </div>
            <div class="card-body">
                <div>
                    <div v-if="buscando" id="buscando">
                        <h5>Buscando...</h5>
                        <i class="fa fa-cog fa-spin fa-2x"></i>
                    </div>
                    <div align="center" v-show="!buscando">
                        <div style="border:1px dashed grey; padding: 10px;" class="col-12 col-sm-6">
                            <h1>Día @{{ dia.dia }}</h1>
                            <audio v-if="ejemplo.audio!=''" :src="ejemplo.audio" controls></audio>
                            <p id="comentarios"></p>
                            <img :src="ejemplo.imagen" height="200" @click="mostrarImagen(ejemplo.imagen)">
                        </div>
                        <hr>
                        <div>
                            <div style="border:5px dashed grey; padding: 10px;" class="col-12 col-sm-6"
                                 @drop.prevent="agregarImagen($event)" @dragover.prevent>
                                <label for="file" class="custom-file-upload">
                                    <i class="fa fa-image"></i> Sube tu foto aquí
                                </label>
                                <br>
                                <div v-if="loading">
                                    <span class="small">Estamos procesando la imagen, porfavor espera un momento...</span>
                                    <br>
                                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                                </div>
                                <div v-else>
                                    <span class="small">O arrastra la imagen desde tu computadora</span>
                                    <br>
                                    <img :src="dia.imagen" height="200" @click="mostrarImagen(dia.imagen)">
                                </div>
                                <input id="file" type="file" accept="image/x-png,image/jpg,image/jpeg"
                                       @change="agregarImagen($event)">
                                <br>
                                <p>@{{ dia.comentario }}</p>
                            </div>
                        </div>
                        <form-error name="imagen" :errors="errors"></form-error>
                    </div>
                </div>
                <div class="comida d-flex justify-content-between">
                    <h4>Calendario</h4>
                    <span>@{{ maximo }} / @{{ teoricos }}</span>
                </div>
                <div class="d-flex">
                    <div class="d-flex justify-content-between col-10 col-sm-6 m-auto">
                        <button v-if="semana>1" class="btn btn-sm btn-light" @click="mostrarSemana(semana-1)">
                            <i v-if="semana>1" class="fa fa-arrow-left"></i>
                            <i v-else></i>
                        </button>
                        <i v-else></i>
                        <h4>Semana @{{ semana }}</h4>
                        <button v-if="maximo>=(((semana - 1) * 7)+dias)" class="btn btn-sm btn-light" @click="mostrarSemana(semana+1)">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                        <i v-else></i>
                    </div>
                </div>
                <div class="d-flex flex-wrap ">
                    <div v-for="d in dias" :class="d>dias?'nodia':'dia'" @click="getDia(((semana-1)*7)+d)">
                        <a>@{{ ((semana-1)*7)+d }}</a>
                    </div>
                </div>
            </div>
            <modal ref="imagenModal" :showok="false" :showcancel="false">
                <img :src="imagen" style="width: 700px">
            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>
        Vue.component('dias', {
            template: '#dias-template',
            props: ['p_dias', 'p_semana','maximo','teoricos'],
            data: function () {
                return {
                    loading: false,
                    buscando: false,
                    imagen: '',
                    errors: {},
                    ejemplo: {
                        imagen: '',
                        comentarios: ''
                    },
                    dia: {
                        dia: 1,
                        imagen: '',
                        comentarios: ''
                    },
                    semana: 1,
                    dias: 1,
                    imagen: ''
                }
            },
            methods: {
                agregarImagen: function (event) {
                    let imagen = null;
                    if (event.dataTransfer == undefined) {
                        imagen = event.target.files[0];
                    } else {
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
                    axios.get('{{url('/reto/getDia/')}}/' + dia).then(function (response) {
                        vm.dia = response.data.dia;
                        vm.ejemplo = response.data.ejemplo;
                        document.getElementById('comentarios').innerHTML = vm.ejemplo.comentario;
                        vm.buscando = false;
                        window.scrollTo(50, 200);
                    });
                },
                mostrarImagen: function (imagen) {
                    this.imagen = imagen;
                    this.$refs.imagenModal.showModal();
                },
                mostrarSemana: function (semana) {
                    let vm = this;
                    axios.get('{{url('/reto/getSemanaCliente/')}}/' + semana).then(function (response) {
                        vm.dias = response.data;
                        vm.semana = semana;
                    });
                }
            },
            created: function () {
                this.semana = this.p_semana;
                this.dias = this.p_dias;
            },
            mounted: function () {
                this.getDia(((this.semana - 1) * 7) + this.dias);
            }
        });

        let vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
