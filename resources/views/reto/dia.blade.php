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
            padding: 10px;
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

        .col-sm-4{
            padding-left: 2px;
        }

        .dia{
            border: 1px solid grey;
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
            flex-grow:1;
            flex-shrink: 1;
            flex-basis: 0;
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
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <dia :p_dia="{{$dia}}" :genero="'{{$genero}}'" :objetivo="'{{$objetivo}}'" :dias="{{$dias}}" p_lugar="{{$lugar}}"></dia>
        </div>
    </div>

    <template id="dia-template">
        <div class="card">
            <div class="card-header">
                <span>Dia @{{ dia.dia }} : @{{ dia.nota }}</span>
            </div>
            <div class="card-body">
                @if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE)

                <div class="d-flex justify-content-between">
                    <span></span>
                    <div id="modo">
                        <table>
                            <tr>
                                <td style="padding-bottom: 8px;">Entrenar desde GYM</td>
                                <td><label class="switch">
                                        <input type="checkbox" v-model="lugar" @change="cambiarLugar(true)">
                                        <span class="slider round"></span>
                                    </label></td>
                                <td class="font-weight-bold" style="padding-bottom: 8px;">Casa</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
                <div v-for="(comida, iComida) in dia.comidas">
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
                    <h4 class="comida">Suplementos</h4>
                    <div>
                        <div v-for="suplemento in dia.suplementos" class="suplemento">
                            <span class="col-12 col-sm-6">@{{ suplemento.suplemento }}</span>
                            <span class="col-12 col-sm-6">@{{ suplemento.porcion }}</span>
                        </div>
                    </div>
                    <h6>¿Aún no cuentas con tus suplementos?</h6>
                    <a href="{{env("APP_TIENDA")}}" class="btn btn-sm btn-success" target="_blank">
                        Pídelos aquí <i class="fa fa-shopping-cart"></i>
                    </a>
                </div>
                <hr>
                <div>
                    <h4 class="comida">Ejercicios</h4>
                    <div v-if="ejercicios != null">
                        <div v-for="(serie, iserie) in ejercicios">
                            <div>
                                <h5>@{{ serie.nombre }}</h5>
                            </div>
                            <div v-for="ejercicio in serie.ejercicios" class="ejercicio">
                                <div class="d-flex flex-wrap">
                                    <a class="col-sm-4" @click="mostrarVideo(ejercicio)">@{{ ejercicio.ejercicio }}</a>
                                    <div class="col-sm-2" v-for="subserie in ejercicio.subseries">@{{ subserie.repeticiones }}</div>
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
                    <h4 class="comida">Cardio</h4>
                    <div style="display: flex; flex-wrap:wrap;" v-if="dia.cardio!=null">
                        <div v-for="ejercicio in dia.cardio" class="ejercicio">
                            <a @click="mostrarVideo(ejercicio)">@{{ ejercicio.ejercicio }}</a>
                        </div>
                    </div>
                    <div v-else>
                        [Aún no hay ejercicios cardio asignados]
                    </div>
                </div>
                    @endif
                    <div>
                        <h4 class="comida">Calendario @{{ semana }}</h4>
                        <div class="col-12" v-for="(sem, index) in semanas">
                            <h6 v-if="(index)<semana" class="font-weight-bold">Semana @{{ index+1  }}</h6>
                            <div v-if="(index)<=semana" class="d-flex flex-wrap ">
                                <div v-for="d in sem" :class="d>dias?'nodia':'dia'" @click="getDia(d)">
                                    <a v-if="d<=dias">
                                        @{{ d }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <modal ref="modal" :showfooter="false" :btncerrar="true" :title="tituloModal">
                <div style="padding-top: 15px;">
                    <video poster="{{asset('/img/poster.png')}}" preload="none" controls="auto" :src="url"
                           style="max-width: 20vmax; min-width: 40vmin; display: block; margin: auto" height="200" >
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
            props: ['p_dia', 'genero', 'objetivo','dias', 'p_lugar'],
            data: function () {
                return {
                    dia: {},
                    url: '',
                    lugar: false,
                    ejercicios: [],
                    correoEnv: false,
                    load: false,
                    tituloModal: '',
                    semanas:[],
                    semana:1
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
                    if (dia<=this.dias){
                        window.location.href = '{{url('/reto/dia/')}}/'+dia+'/'+this.genero+'/'+this.objetivo;
                    }
                }
            },
            created: function () {
                this.dia = this.p_dia;
                this.dia.nota = this.p_dia.nota.descripcion;
                for(let i = 0; i < 56; i++){
                    if(i+1==this.dias){
                        this.semana = parseInt((i/7)+1);
                    }
                    if(i % 7 == 0){
                        this.semanas.push([]);
                    }
                    this.semanas[parseInt(i/7)].push(i+1);
                }
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