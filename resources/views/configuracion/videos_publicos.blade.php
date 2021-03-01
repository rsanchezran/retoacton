@extends('layouts.app')
@section('header')
    <style>
        .ejercicio {
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 5px;
            width: 300px;
            margin: 5px;
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

        .disabled {
            background-color: #e1e1e8;
        }

        label {
            font-weight: bold;
        }

        hr {
            margin: 2px;
            margin-top: 20px;
        }

        #pendientes, #mostrarPendientes {
            background-color: #fff;
            border: 2px solid grey;
            position: absolute;
            bottom: 20px;
            right: 100px;
            text-align: center;
        }

    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <div class="row justify-content-center">
                <inicio :p_videos="{{$videos}}" :p_categorias="{{$categorias}}"
                        :p_pendientes="{{$pendientes}}"></inicio>
            </div>
        </div>
    </div>

    <template id="videos-template">
        <div>
            <div v-if="loading">
                <span>Procesando información</span>
                <br>
                <i v-if="loading" class="fa fa-2x fa-cog fa-spin"></i>
            </div>
            <div v-show="!loading" class="card">

                <div class="card-body">
                    <div class="d-flex flex-wrap">
                            <label>Video de {{ $nombre }}</label>

                            <video :id="1" width="520" height="340" controls :src="{{ $videos }}"
                                   poster="{{asset('/img/poster.png')}}" preload="none" controls="auto">
                                <source :src="{{ $videos }}" type="video/mp4">
                            </video>

                        </div>
                        <br>
                </div>
            </div>


        </div>
    </template>

@endsection
@section('scripts')
    <script>
        Vue.component('inicio', {
            template: '#videos-template',
            props: ['p_videos', 'p_categorias', 'p_pendientes'],
            data: function () {
                return {
                    categorias: [],
                    pendientes: [],
                    prueba: '',
                    errors: {},
                    loading: false,
                    buscando: false,
                    mostrarPendientes: true,
                    tarea:null,
                    categoria:null,
                    video_nuevo: ''
                }
            },
            methods: {
                validarArchivo: function (pendiente) {
                    let archivo = pendiente.split('.');
                    if (archivo.length > 1){
                        if (archivo[archivo.length-1] != 'mp4'){
                            return "[Formato incorrecto]";
                        }
                    }else{
                        return "[Formato incorrecto]";
                    }
                    return "";
                },
                cambiarVisualizacion: function (categoria) {
                    categoria.mostrar = !categoria.mostrar;
                },
                subirCategoria: function(categoria){
                    let vm = this;
                    axios.post("{{url('/configuracion/categoria')}}", categoria).then(function (respuesta) {
                        vm.loading = false;
                        categoria.nueva = false;
                    }).catch(function (error) {
                        vm.loading = false;
                        vm.errors = error.response.data.errors;
                    });
                },
                subirEjercicios: function (event, categoria) {
                    let vm = this;
                    this.categoria = categoria;
                    vm.errors = {};
                    vm.loading = true;
                    let files = event.target.files;
                    let size = 0;
                    _.each(files, function (file, i) {
                        size += Math.round(((file.size / 1024) / 1024) * 100) / 100;
                    });
                    if (size >= 320.0) {
                        this.errors[categoria.nombre] = ['El archivo debe ser menor a 320MB '];
                        vm.loading = false;
                    } else {
                        let formData = new FormData();
                        formData.append('categoria', categoria.id);
                        formData.append('nombre', categoria.nombre);
                        _.each(files, function (file, i) {
                            let nombre = file.name.replace(' ', '_');
                            formData.append('archivos[' + i + ']', file);
                            formData.append('nombres[' + i + ']', nombre);

                        });
                        axios.post("{{url('/configuracion/ejercicio')}}", formData).then(function (respuesta) {
                            vm.loading = false;
                            vm.getVideosPendientes();
                        }).catch(function (error) {
                            vm.loading = false;
                            vm.errors = error.response.data.errors;
                        });
                    }
                },
                subirVideo: function (event, nombre) {
                    let vm = this;
                    let file = event.target.files[0];
                    let fileSize = Math.round(((file.size / 1024) / 1024) * 100) / 100;
                    vm.errors = {};
                    vm.loading = true;
                    if (fileSize <= 320.0) {
                        let fm = new FormData();
                        fm.append('video', file);
                        fm.append('nombre', nombre);

                        axios.post('{{url('configuracion/video')}}', fm).then(function (response) {
                            if (response.data.status == 'ok') {
                                vm.ejercicios.push(response.data.videoNuevo);
                            }
                            vm.loading = false;
                        }).catch(function (error) {
                            vm.loading = false;
                            vm.errors = error.response.data.errors;
                        });
                    } else {
                        vm.loading = false;
                        vm.errors[nombre] = ['El archivo debe ser menor a 300MB']
                    }
                },
                getNombre: function (nombre) {
                    return nombre.replace(/_/g, " ");
                },
                quitarEjercicio: function (categoria, ejercicio) {
                    let vm = this;
                    vm.loading = true;
                    axios.post('{{url('configuracion/quitarEjercicio')}}', {
                        categoria: categoria.nombre,
                        ejercicio: ejercicio
                    }).then(function () {
                        vm.loading = false;
                        vm.getEjercicios(categoria);
                    }).catch(function () {
                        vm.loading = false;
                    });
                },
                getEjercicios: function (categoria) {
                    let vm = this;
                    vm.buscando = true;
                    axios.get('{{url('configuracion/getEjerciciosCategoria/')}}/' + categoria.nombre).then(function (response) {
                        categoria.ejercicios = response.data;
                        vm.buscando = false;
                    }).catch(function () {
                        vm.buscando = false;
                    });
                },
                getVideosPendientes: function () {
                    let vm = this;
                    vm.buscando = true;
                    axios.get('{{url('configuracion/getVideosPendientes')}}').then(function (response) {
                        vm.pendientes = response.data;
                        vm.buscando = false;
                        if (vm.pendientes.length>0){
                            if (vm.tarea==null){
                                vm.tarea = setInterval(vm.getVideosPendientes,30000);
                            }
                        }else{
                            clearInterval(vm.tarea);
                            vm.getEjercicios(vm.categoria);
                        }
                    }).catch(function (response) {
                        vm.buscando = false;
                    });
                },
                agregarCategoria: function () {
                    this.categorias.push({id:null,nombre:'Categoría',ejercicios:[], nueva:true});
                }
            },
            created: function () {
                this.categorias = this.p_categorias;
                this.pendientes = this.p_pendientes;
            },
            mounted: function () {
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
