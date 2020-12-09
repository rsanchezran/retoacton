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
            border: 1px solid grey;
            text-align: center;
            margin: 5px;
            padding: 5px;
            cursor: pointer;
            flex-grow:1;
            flex-shrink: 1;
            flex-basis: 0;
            font-weight: bold;
            color: #0080DD;
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
                                <td style="padding-bottom: 8px;">Entrenar desde <b style="font-family: unitext">GYM</b></td>
                                <td><label class="switch">
                                        <input type="checkbox" v-model="lugar" @change="cambiarLugar(true)">
                                        <span class="slider round"></span>
                                    </label></td>
                                <td style="padding-bottom: 8px;"><b style="font-family: unitext">CASA</b></td>
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
                    <div class="d-block ml-auto mr-auto text-center">
                        <h6 class="font-weight-bold">¿Aún no cuentas con tus suplementos?</h6>
                        <a href="{{env("APP_TIENDA")}}" class="btn btn-sm btn-warning" target="_blank">
                            Pídelos aquí <i class="fa fa-shopping-cart"></i>
                        </a>
                    </div>
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
                    <h4 class="comida">Cardio</h4>
                    <div style="display: flex; flex-wrap:wrap;" v-if="dia.cardio!=null">
                        <div v-for="ejercicio in dia.cardio" class="ejercicio">
                            <span>@{{ ejercicio.ejercicio }}</span>
                        </div>
                    </div>
                    <div v-else>
                        [Aún no hay ejercicios cardio asignados]
                    </div>
                </div>
                    @endif
                    <div>
                        <h4 class="comida">Calendario</h4>
                        <div class="d-flex m-auto col-12 col-sm-6">
                            AQUI @{{semana}}
                            <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana)">
                                <option  v-for="s in semana" :value="s">Semana @{{ s }}</option>
                            </select>
                        </div>
                        <div class="d-flex flex-wrap ">
                            <div v-for="d in 7" class="dia" @click="getDia(((semana-1)*7)+d)">
                                <a @click="getDia(((semana-1)*7)+d )">@{{ ((semana-1)*7)+d }}</a>
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
