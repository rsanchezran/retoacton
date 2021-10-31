@extends('layouts.app')
@section('header')
    <style>
        .suplemento {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 10px;
            box-shadow: 0px -1px 11px -9px black;
            margin: 5px;
            display: flex;
            flex-wrap: wrap;
        }

        .ejercicio {
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 5px;
            box-shadow: 0px -1px 11px -9px black;
            margin: 5px;
        }

        .ejercicio span{
            margin:0 10px;
        }

        .ejercicio a{
            color: #1d68a7 !important;
            text-decoration: underline !important;
            cursor: pointer;
        }

        h4, h5 {
            font-weight: bold;
        }

        .card-header a {
            color: #FFF;
        }

        .comida {
            background-color: #007FDC;
            color: #FFF;
            padding: 10px;
        }

        .col-4, .col-2{
            padding: 2px;
        }

        .dia{
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
            flex-grow:1;
            flex-shrink: 1;
            flex-basis: 0;
            font-weight: bold;
            color: #999999;
            background: #F2F2F2;
            border-radius: 5px;
        }

        .nodia{
            border: 1px solid #FFF;
            text-align: center;
            margin: 5px;
            padding: 5px;
            flex-grow:1;
            flex-shrink: 1;
            flex-basis: 0;
        }

        .btn-warning{
            background-color: #FF9900;
            color: #fff;
            padding: 10px;
        }

        .card-header-new{
            background: transparent !important;
        }
        .card{
            font-family: 'Nunito' !important;
            border: 0px solid;
        }
        .card-header-new span{
            font-size: 40px !important;
            font-family: 'Nunito' !important;
            color: #0080DD !important;
        }
        .entrenamiento{
            color: #666666;
            font-size: 20px;
        }
        input:checked + .slider {
            background-color: #0080DD !important;
        }
        .comida {
            background-color: transparent !important;
            color: #666;
            padding: 10px;
        }
        .ejercicio{
            border-bottom: 0px solid rgba(0, 0, 0, 0.125);
            border-radius: 0px;
            padding-left: 10px;
            box-shadow: 0px 0px 0px 0px black;
            margin: 0px;
            color: #666;
        }
        .suplemento {
            border-bottom: 0px solid rgba(0, 0, 0, 0.125);
            border-radius: 0px;
            padding: 10px;
            box-shadow: 0px 0px 0px 0px black;
            margin: 5px;
            display: flex;
            flex-wrap: wrap;
            color: #666;
        }
        .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
            width: 100% !important;
        }
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <dia :p_dia="{{$dia}}" :genero="'{{$genero}}'" :objetivo="'{{$objetivo}}'" :p_dias="{{$dias}}"
                 p_lugar="{{$lugar}}" :p_semana="{{$semana}}" :maximo="{{$maximo}}" :teorico="{{$teorico}}" :diasReto="{{$diasReto}}" ></dia>
        </div>
    </div>

    <template id="dia-template">
        <div class="card">
            <div class="card-header-new text-center">
                <span>DÍA @{{ dia.dia }}</span>
            </div>
            <div class="card-body">
                @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)

                <div class="d-flex justify-content-between text-center  ">
                    <div id="modo" class="text-center col-8 offset-2">
                        <div class="entrenamiento"> Entrenamiento en:</div>
                        <strong style="" class="mt-5">GYM</strong>
                        <label class="switch mt-4">
                            <input type="checkbox" v-model="lugar" @change="cambiarLugar(true)">
                            <span class="slider round"></span>
                        </label>
                        <b style="" class="mt-5">CASA</b>
                    </div>
                </div>
                @endif
                <div class="col-12">
                    <img src="{{asset('images/2021/dieta.png')}}" class="mt-3 mb-3" width="50%">
                </div>
                <div class="ml-4" v-for="(comida, iComida) in dia.comidas">
                    <h5 class="comida">Comida @{{ iComida+1 }}</h5>
                    <div>
                        <div v-for="alimento in comida" class="ejercicio">
                            @{{ alimento.alimento }}
                        </div>
                    </div>
                    <hr>
                </div>
                @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)
                <div>
                    <div class="col-12">
                        <img src="{{asset('images/2021/rutina.png')}}" class="mt-3 mb-3" width="50%">
                    </div>
                    <div v-if="ejercicios != null">
                        <div v-for="(serie, iserie) in ejercicios">
                            <div  style="padding-left: 30px !important;">
                                <h5>@{{ serie.nombre }}</h5>
                            </div>
                            <div v-for="ejercicio in serie.ejercicios" class="ejercicio" style="padding-left: 40px !important;">
                                <div>
                                    <div>
                                        <a @click="mostrarVideo(ejercicio)">@{{ ejercicio.ejercicio }}</a>
                                    </div>
                                    <div v-for="subserie in ejercicio.subseries">@{{ subserie.repeticiones }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        [Aún no hay ejercicios asignados]
                    </div>
                </div>
                <hr>
                <div>
                    <h4 class="comida" style="padding-left: 30px !important;">Cardio</h4>
                    <div style="display: flex; flex-wrap:wrap;" v-if="dia.cardio!=null">
                        <div v-for="ejercicio in dia.cardio" class="ejercicio" style="padding-left: 30px !important;">
                            <span>@{{ ejercicio.ejercicio }}</span>
                        </div>
                    </div>
                    <div v-else>
                        [Aún no hay ejercicios cardio asignados]
                    </div>
                </div>
                    <hr>


                        <div class="col-12">
                            <img src="{{asset('images/2021/suplementos.png')}}" class="mt-3 mb-3" width="50%">
                        </div>
                        <div>
                            <div>
                                <div v-for="suplemento in dia.suplementos" class="suplemento">
                                    <span class="col-12 col-sm-6">@{{ suplemento.suplemento }}</span>
                                    <span class="col-12 col-sm-6">@{{ suplemento.porcion }}</span>
                                </div>
                            </div>
                            <div class="d-block ml-auto mr-auto text-center">
                                <h6 class="font-weight-bold">¿Aún no cuentas con tus suplementos?</h6>
                                <a href="{{env("APP_TIENDA")}}" class="btn btn-sm btn-warning" target="_blank">
                                    Pídelos aquí <i class="fa fa-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div>
                        <h4 class="comida">Calendario</h4>
                        <div class="d-flex m-auto col-12 col-sm-12">
                            <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana)">
                                <option v-for="s in p_semana" :value="s">Semana @{{ s }}</option>
                            </select>
                        </div>
                        <div class="d-flex flex-wrap ">
                            <div v-for="d in dias" class="dia" @click="getDia(((semana-1)*7)+d)">
                                <a @click="getDia(((semana-1)*7)+d )">@{{ ((semana-1)*7)+d }}</a>
                            </div>
                            <div v-for="d in 7-dias" class="nodia">
                            </div>
                        </div>
                    </div>
            </div>
            <modal ref="modal" :showfooter="false" :btncerrar="true" :title="tituloModal">
                <div style="padding-top: 15px;">
                    <video poster="{{asset('/img/poster.png')}}" preload="none" controls="auto" :src="url"
                           width="400" height="200" >
                        <source :src="url" type="video/mp4">
                    </video>
                </div>
            </modal>
        </div>
    </template>
@endsection
@section('scripts')
    <script>

        Vue.component('dia', {
            template: '#dia-template',
            props: ['p_dia', 'genero', 'objetivo','p_dias', 'p_lugar','p_semana', 'maximo', 'teorico', 'diasReto'],
            data: function () {
                return {
                    dia: {},
                    dias: 0,
                    url: '',
                    lugar: false,
                    ejercicios: [],
                    correoEnv: false,
                    load: false,
                    tituloModal: '',
                    semana:2
                }
            },
            methods: {
                mostrarVideo: function (ejercicio) {
                    this.url = '{{url('/configuracion/ejercicio')}}/' + ejercicio.video;
                    this.tituloModal = ejercicio.ejercicio;
                    this.$refs.modal.showModal();
                },
                cambiarLugar: function (actualizar) {
                    if(this.lugar==0){
                        this.ejercicios = this.dia.gym;
                    }else{
                        this.ejercicios = this.dia.casa;
                    }
                    if (actualizar){
                        axios.post('{{url('/cuenta/cambiarModo')}}',{lugar: this.lugar}).then(function (response) {});
                    }
                },
                mostrarSemana: function (semana) {
                    let vm = this;
                    axios.get('{{url('/reto/getSemanaPrograma/')}}/' + semana).then(function (response) {
                        vm.dias = response.data;
                        vm.semana = semana;
                        Vue.nextTick(function () {
                            $('.selectpicker').selectpicker('refresh');
                        });
                    });
                },
                cerrarModal: function(){
                    this.$refs.modal.closeModal();
                },
                enviarCorreo: function (dia, genero, objetivo, lugar) {
                    let vm = this;
                    vm.correoEnv = false;
                    vm.load = true;
                    axios.post("{{url('/reto/correo')}}",{
                        dia: dia,
                        genero: genero,
                        objetivo: objetivo,
                        lugar: lugar,
                        dieta:vm.dia.dieta
                    }).then(function (response) {
                       if(response.data.status == 'ok')
                            vm.correoEnv = true;
                       vm.load = false;
                    }).catch(function (error) {
                        vm.load = false;
                    });
                },
                getDia: function (dia) {
                    window.location.href = '{{url('/reto/dia/')}}/'+dia+'/'+this.genero+'/'+this.objetivo;
                }
            },
            created: function () {
                this.dia = this.p_dia;
                this.dias = this.p_dias;
                this.dia.nota = this.p_dia.nota.descripcion;
                this.semana = this.p_semana;
            },
            mounted: function () {
                this.lugar = this.p_lugar == 1;
                this.cambiarLugar(false);
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
