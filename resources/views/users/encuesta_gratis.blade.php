@extends('layouts.app')
@section('header')
    <style>
        label {
            display: block;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')

    <div id="vue" class="container">
        <temp-encuesta :usuario="{{$usuario}}"></temp-encuesta>
    </div>
    <template id="temp">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>Encuesta: {{$usuario->name.' '.$usuario->last_name }}</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; flex-direction: column">
                    <div v-for="(pregunta,index) in usuario.encuesta">
                        <label>@{{ index+1 }}.- @{{ pregunta.pregunta }}:</label>
                        <span v-if="pregunta.multiple==0 || pregunta.multiple==null">@{{ pregunta.respuesta}}</span>
                        <div v-else>
                            <span v-for="(respuesta, index) in pregunta.respuesta" >
                                @{{ respuesta + ((pregunta.respuesta.length-1)==index?'.':',')}}
                            </span>
                        </div>
                        <hr>
                    </div>
                    <div v-if="usuario.encuesta.length==0" align="center">
                        <h5>[No hay datos para mostrar]</h5>
                    </div>
                </div>
            </div>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-encuesta', {
            template: '#temp',
            props: ['usuario'],
        })

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection