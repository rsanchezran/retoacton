@extends('layouts.app')
@section('header')
    <style>
        .card .card {
            height: 200px;
        }

        #dias {
            display: flex;
            flex-wrap: wrap;
        }

        #dias .card {
            width: 16rem !important;
        }

        .card-body {
            padding: 10px;
        }

        .modoActivo {
            background-color: #0080DD;
            border-color: #0080DD;
            color: #FFF;
        }

        #modos {
            border: 1px solid lightgray;
            padding: 8px;
            margin-bottom: 10px;
            justify-content: space-between;
            display: flex;
        }

        #panel {
            position: fixed;
            padding: 10px;
            right: 20px;
            bottom: 120px;
            display: flex;
            flex-direction: column;
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <programa :p_dias="{{$dias}}" :p_semana="{{$semana}}" :maximo="{{$maximo}}" :teorico="{{$teorico}}"></programa>
    </div>

    <template id="programa-template">
        <div class="container">
            <div class="card">
                <div class="card-header"><i class="fa fa-calendar-alt"></i> Programa de ejercicios</div>
                <div class="card-body">
                    <p>Bienvenido a la configuración del programa de ejercicios, recuerda que aquí debes configurar los
                        diferentes ejercicios que los clientes realizarán
                        de acuerdo al objetivo que quieren alcanzar y al género que contestaron en la encuesta inicial
                    </p>
                    <div id="modos">
                        <div class="d-flex m-auto">
                            <button v-if="semana>1" class="btn btn-sm btn-light" @click="mostrarSemana(semana-1)">
                                <i v-if="semana>1" class="fa fa-arrow-left"></i>
                                <i v-else></i>
                            </button>
                            <i v-else></i>
                            <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana)">
                                <option v-for="s in p_semana" :value="s">Semana @{{ s }}</option>
                            </select>
                            <button v-if="maximo>=semana * dias.length" class="btn btn-sm btn-light"
                                    @click="mostrarSemana(semana+1)">
                                <i class="fa fa-arrow-right"></i>
                            </button>
                            <i v-else></i>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-default" :class="modo=='0-0'?'modoActivo':''"
                                    @click="mostrarModo(0,0)">
                                <i class="fa fa-male"></i> <i class="fa fa-arrow-down"></i> <span>Hombre bajar</span>
                            </button>
                            <button class="btn btn-sm btn-default" :class="modo=='0-1'?'modoActivo':''"
                                    @click="mostrarModo(0,1)">
                                <i class="fa fa-male"></i> <i class="fa fa-arrow-up"></i> <span>Hombre subir</span>
                            </button>
                            <button class="btn btn-sm btn-default" :class="modo=='1-0'?'modoActivo':''"
                                    @click="mostrarModo(1,0)">
                                <i class="fa fa-female"></i> <i class="fa fa-arrow-down"></i> <span>Mujer bajar</span>
                            </button>
                            <button class="btn btn-sm btn-default" :class="modo=='1-1'?'modoActivo':''"
                                    @click="mostrarModo(1,1)">
                                <i class="fa fa-female"></i> <i class="fa fa-arrow-up"></i> <span>Mujer subir</span>
                            </button>
                        </div>
                    </div>
                    <div id="dias">
                        <div v-for="dia in dias" class="card" @click="configurar(dia.dia, genero,objetivo)" style="width: 13rem;">
                            <div class="card-header">Día @{{ dia.dia }}</div>
                            <div class="card-body">
                                @{{ dia.ejercicios }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="panel" v-if="maximo >= teorico && semana==p_semana">
                <button class="btn btn-sm btn-success" @click="agregarDia">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
    </template>
@endsection
@section('scripts')
    <script>

        Vue.component('programa', {
            template: '#programa-template',
            props: ['p_dias', 'p_semana', 'maximo', 'teorico'],
            data: function () {
                return {
                    dias: [],
                    semana: 1,
                    errors: [],
                    lugar: false,
                    disableModal: true,
                    genero:0,
                    objetivo:0,
                    modo: '0-0'
                }
            },
            methods: {
                configurar: function (dia, genero, objetivo) {
                    window.location.href = '{{url('configuracion/dia')}}/' + dia + '/' + genero + '/' + objetivo;
                },
                mostrarSemana: function (semana) {
                    let vm = this;
                    axios.get('{{url('/configuracion/programa/getSemanaEjercicios/')}}/' + semana).then(function (response) {
                        vm.dias = response.data;
                        vm.semana = semana;
                        localStorage.setItem('semana', vm.semana);
                        Vue.nextTick(function () {
                            $('.selectpicker').selectpicker('refresh');
                            let modo = vm.modo.split('-')
                            vm.mostrarModo(modo[0], modo[1]);
                        });
                    });
                },
                mostrarModo: function (genero, objetivo) {
                    localStorage.setItem('genero', genero);
                    localStorage.setItem('objetivo', objetivo);
                    this.genero = genero;
                    this.objetivo = objetivo;
                    let modo = genero + '-' + objetivo;
                    this.modo = modo;
                    _.each(this.dias, function (dia) {
                        if (dia.ejerciciosG[modo] == null) {
                            dia.ejercicios = "Sin ejercicios";
                        } else {
                            dia.ejercicios = Object.values(dia.ejerciciosG[modo])[0].map(function (ejercicio) {
                                return ejercicio.ejercicio;
                            }).join(' , ');
                        }
                    });
                },
                agregarDia: function () {
                    let ultimoDia=(this.semana-1)*7+this.dias.length+1;
                    this.dias.push({
                        dia:ultimoDia,
                        mostrar: true,
                        subir: true,
                        loading: false,
                        comentarios: '',
                        imagen: '',
                        audio: '',
                    });
                },
            },
            created: function () {
                this.dias = this.p_dias;
                if (localStorage.getItem('semana') != null) {
                    this.semana = localStorage.getItem('semana');
                    this.mostrarSemana(this.semana);
                }else{
                    this.semana = this.p_semana;
                }
            },
            mounted: function () {
                if (localStorage.getItem('genero') != null) {
                    this.mostrarModo(localStorage.getItem('genero'),localStorage.getItem('objetivo'))
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
