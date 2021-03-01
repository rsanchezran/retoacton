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
            <div v-for="(v, index) in p_videos" class="col-sm-4">
                <label>Video de @{{ v.nombre }}</label>
                <label :for="'video'+index" :class="loading?'disabled':''" class="custom-file-upload">
                    <i class="fa fa-cloud-upload"></i> Subir
                </label>
                <input :id="'video'+index" type="file" @change="subirVideo($event, v.nombre)"
                       :disabled="loading">
                <br>
                <video :id="'v'+index" width="320" height="240" controls :src="v.src"
                       poster="{{asset('/img/poster.png')}}" preload="none" controls="auto">
                    <source :src="v.src" type="video/mp4">
                </video>
                <form-error :name="v.nombre.replace(' ','_')" :errors="errors"></form-error>
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
                    categoria:null
                }
            },
            methods: {

            },
            created: function () {
            },
            mounted: function () {
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
