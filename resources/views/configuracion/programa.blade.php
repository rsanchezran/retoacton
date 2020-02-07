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

        .modoActivo{
            background-color: #0080DD;
            border-color: #0080DD;
            color: #FFF;
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
                    <span>Bienvenido a la configuración del programa de ejercicios, recuerda que aquí debes configurar los diferentes ejercicios que los clientes realizarán
                    de acuerdo al objetivo que quieren alcanzar y al género que contestaron en la encuesta inicial</span>
                    <div class="d-flex align-items-end">
                        <button class="btn btn-sm btn-default" :class="modo=='0-0'?'modoActivo':''" @click="mostrarModo(0,0)">
                            <i class="fa fa-male"></i> <i class="fa fa-arrow-down"></i> <span>Hombre bajar</span>
                        </button>
                        <button class="btn btn-sm btn-default" :class="modo=='0-1'?'modoActivo':''" @click="mostrarModo(0,1)">
                            <i class="fa fa-male"></i> <i class="fa fa-arrow-up"></i> <span>Hombre subir</span>
                        </button>
                        <button class="btn btn-sm btn-default" :class="modo=='1-0'?'modoActivo':''" @click="mostrarModo(1,0)">
                        <i class="fa fa-female"></i> <i class="fa fa-arrow-down"></i> <span>Mujer bajar</span>
                        </button>
                        <button class="btn btn-sm btn-default" :class="modo=='1-1'?'modoActivo':''" @click="mostrarModo(1,1)">
                        <i class="fa fa-female"></i> <i class="fa fa-arrow-up"></i> <span>Mujer subir</span>
                        </button>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between col-10 col-sm-6 m-auto">
                        <button v-if="semana>1" class="btn btn-sm btn-light" @click="mostrarSemana(semana-1)">
                            <i v-if="semana>1" class="fa fa-arrow-left"></i>
                            <i v-else></i>
                        </button>
                        <i v-else></i>
                        <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana)">
                            <option v-for="s in p_semana" :value="s">Semana @{{ s }}</option>
                        </select>
                        <button v-if="maximo>=semana * dias.length" class="btn btn-sm btn-light" @click="mostrarSemana(semana+1)">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                        <i v-else></i>
                    </div>
                    <div class="dia">
                        <div v-for="dia in dias" class="card" @click="configurar(dia.dia, 1,1)" style="width: 13rem;">
                            <div class="card-header">Día @{{ dia.dia }}</div>
                            <div class="card-body">
                                @{{ dia.ejercicios }}
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
            props: ['p_dias', 'p_semana','maximo','teorico'],
            data: function () {
                return {
                    dias: [],
                    semana: 1,
                    errors: [],
                    lugar:false,
                    disableModal:true,
                    modo:'0-0'
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
                        Vue.nextTick(function () {
                            $('.selectpicker').selectpicker('refresh');
                            let modo = vm.modo.split('-')
                            vm.mostrarModo(modo[0], modo[1]);
                        });
                    });
                },
                mostrarModo: function (genero, objetivo) {
                    let modo = genero+'-'+objetivo;;
                    this.modo = modo;
                    _.each(this.dias, function (dia) {
                        if(dia.ejerciciosG[modo] == null){
                            dia.ejercicios = "Sin ejercicios";
                        }else{
                            dia.ejercicios = dia.ejerciciosG[modo][0].map(function (ejercicio) {
                                    return ejercicio.ejercicio;
                                }).join(' , ');
                        }
                    });
                }
            },
            created: function () {
                this.dias = this.p_dias;
                this.semana = this.p_semana;
            },
            mounted:function () {
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection