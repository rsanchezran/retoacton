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
                <inicio ></inicio>
            </div>
        </div>
    </div>

    <template id="videos-template">
    </template>
    <div>


        <div class="card col-md-10 mx-auto">
            <div class="card-header">
                <h1>Video de {{ $nombre }}</h1>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap">

                    <video width="720" height="480" controls src="{{ $videos }}"
                           poster="{{asset('/img/header.png')}}" preload="none" controls="auto" class="col-md-10 mx-auto">
                        <source src="{{ $videos }}" type="video/mp4">
                    </video>

                </div>
                <br>
            </div>
        </div>



    </div>

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
