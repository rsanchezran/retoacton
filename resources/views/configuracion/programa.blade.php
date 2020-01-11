@extends('layouts.app')
@section('header')
    <style>
        .card .card {
            height: 200px;
        }

        .dia {
            display: flex !important;
            flex-wrap: wrap;
        }

        .dia .card {
            width: 12rem;
        }

        table.sticky{
            position: sticky;
            top: 0;
            margin-bottom: 0px;
            background-color: white;
            box-shadow: 0px 15px 10px -20px grey;
        }

        textarea{
            pointer-events: none;
            border: none;
            resize: none;
            overflow: hidden;
        }

        .card-body{
            padding: 10px;
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <div class="row justify-content-center">
                <programa :p_dias="{{$dias}}" :usuario="{{$usuario}}"></programa>
            </div>
        </div>
    </div>

    <template id="programa-template">
        <div class="card">
            <div class="card-header"><i class="fa fa-calendar-alt"></i> Programa</div>
            <div class="card-body">
                <span>Bienvenido a la configuración del programa de ejercicios, recuerda que aquí debes configurar los diferentes ejercicios que los clientes realizarán
                de acuerdo al objetivo que quieren alcanzar y al género que contestaron en la encuesta inicial</span>
                <hr>
                <div style="display: flex; justify-content: space-between">
                    <ul id="opciones" class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a :class="'nav-link '+isActive(usuario,0,0)" data-toggle="tab" href="#hombreb" role="tab"
                               aria-selected="false" v-if="mostrarTab(usuario,0,0)">Hombre bajar</a>
                        </li>
                        <li class="nav-item">
                            <a :class="'nav-link '+isActive(usuario,0,1)" data-toggle="tab" href="#hombres" role="tab"
                               aria-selected="true" v-if="mostrarTab(usuario,0,1)">Hombre subir</a>
                        </li>
                        <li class="nav-item">
                            <a :class="'nav-link '+isActive(usuario,1,0)" data-toggle="tab" href="#mujerb" role="tab"
                               aria-selected="false" v-if="mostrarTab(usuario,1,0)">Mujer bajar</a>
                        </li>
                        <li class="nav-item">
                            <a :class="'nav-link '+isActive(usuario,1,1)" data-toggle="tab" href="#mujers" role="tab"
                               aria-selected="false" v-if="mostrarTab(usuario,1,1)">Mujer subir</a>
                        </li>
                    </ul>
                    <div>
                        @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                            <toggle-button v-model="lugar"
                                           :labels="{checked: 'Casa', unchecked: 'Gym'}"></toggle-button>
                        @endif
                    </div>
                </div>
                <div class="tab-content">
                    <div :class="'tab-pane fade '+isActiveTab(usuario,0,0)" id="hombreb" role="tabpanel">
                        <div class="dia">
                            <div v-for="dia in dias" class="card" @click="configurar(dia.dia, 0,0)" style="width: 13rem;"
                                 v-if="mostrarTab(usuario,0,0)">
                                <div class="card-header">Día @{{ dia.dia }}</div>
                                <div class="card-body">
                                    <div v-if="dia.ejerciciosG['0-0']!=null">
                                            <textarea cols="17" rows="6" readonly
                                            >@{{ mostrarTexto(dia.ejerciciosG['0-0'][lugar?1:0]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div :class="'tab-pane fade '+isActiveTab(usuario,0,1)" id="hombres" role="tabpanel">
                        <div class="dia">
                            <div v-for="dia in dias" class="card" @click="configurar(dia.dia,0,1)" style="width: 13rem;"
                                 v-if="mostrarTab(usuario,0,1)">
                                <div class="card-header">Día @{{ dia.dia }}</div>
                                <div class="card-body">
                                    <div v-if="dia.ejerciciosG['0-1']!=null">
                                        <textarea cols="17" rows="6" readonly
                                        >@{{ mostrarTexto(dia.ejerciciosG['0-1'][lugar?1:0]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div :class="'tab-pane fade '+isActiveTab(usuario,1,0)" id="mujerb" role="tabpanel">
                        <div class="dia">
                            <div v-for="dia in dias" class="card" @click="configurar(dia.dia,1,0)" style="width: 13rem;"
                                 v-if="mostrarTab(usuario,1,0)">
                                <div class="card-header">Día @{{ dia.dia }}</div>
                                <div class="card-body">
                                    <div v-if="dia.ejerciciosG['1-0']!=null">
                                        <textarea cols="17" rows="6" readonly
                                        >@{{ mostrarTexto(dia.ejerciciosG['1-0'][lugar?1:0]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div :class="'tab-pane fade '+isActiveTab(usuario,1,1)" id="mujers" role="tabpanel">
                        <div class="dia">
                            <div v-for="dia in dias" class="card" @click="configurar(dia.dia, 1,1)" style="width: 13rem;"
                                 v-if="mostrarTab(usuario,1,1)">
                                <div class="card-header">Día @{{ dia.dia }}</div>
                                <div class="card-body">
                                    <div v-if="dia.ejerciciosG['1-1']!=null">
                                        <textarea cols="17" rows="6" readonly
                                        >@{{ mostrarTexto(dia.ejerciciosG['1-1'][lugar?1:0]) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection
@section('scripts')
    <script>

        Vue.component('programa', {
            template: '#programa-template',
            props: ['p_dias', 'p_suplementos', 'usuario'],
            data: function () {
                return {
                    dias: [],
                    suplementos: [],
                    errors: [],
                    lugar:false,
                    elimSuplementos: [],
                    disableModal:true,
                    suplementosDB: []
                }
            },
            methods: {
                mostrarTexto:function(dias){
                    let texto = '';
                    if(dias != undefined)
                        for (let i=0, limite=dias.length; i<limite && i<5; i++)
                            texto += dias[i].ejercicio+'.\n';

                    return texto;
                },
                configurar: function (dia, genero, objetivo) {
                    if (this.usuario.rol == '{{\App\Code\RolUsuario::ADMIN}}') {
                        window.location.href = '{{url('configuracion/dia')}}/' + dia + '/' + genero + '/' + objetivo;
                    } else {
                        window.location.href = '{{url('reto/dia')}}/' + dia + '/' + genero + '/' + objetivo;
                    }
                },
                mostrarSuplementos: function () {
                    this.$refs.modal.showModal();
                    this.suplementos = this.suplementosDB.slice();
                    this.elimSuplementos = [];
                    this.errors = [];
                    this.disableModal=true;
                },
                mostrarTab: function (usuario, genero, objetivo) {
                    return usuario.rol == 'admin' || (usuario.rol == 'cliente' && usuario.genero == genero && usuario.objetivo == objetivo);
                },
                isActive: function (usuario, genero, objetivo) {
                    if (usuario.rol == 'admin') {
                        if (genero == 0 && objetivo == 0) {
                            return 'active';
                        } else {
                            return '';
                        }
                    } else {
                        return usuario.genero == genero && usuario.objetivo == objetivo ? 'active' : '';
                    }
                },
                isActiveTab: function (usuario, genero, objetivo) {
                    if (usuario.rol == 'admin') {
                        if (genero == 0 && objetivo == 0) {
                            return 'active show';
                        } else {
                            return '';
                        }
                    } else {
                        return usuario.genero == genero && usuario.objetivo == objetivo ? 'active show' : '';
                    }
                }
            },
            created: function () {
                this.dias = this.p_dias;
            },
            mounted:function () {
                if (localStorage.getItem('genero')!=null){
                    if (localStorage.getItem('genero')=='0'){
                        if(localStorage.getItem('objetivo')=='0'){
                            $('#opciones a')[0].click();
                        }else{
                            $('#opciones a')[1].click();
                        }
                    }else{
                        if (localStorage.getItem('objetivo')=='0'){
                            $('#opciones a')[2].click();
                        }else{
                            $('#opciones a')[3].click();
                        }
                    }
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection