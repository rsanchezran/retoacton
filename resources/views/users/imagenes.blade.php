@extends('layouts.app')
@section('header')
    <style>
        .link{
            height: 200px;
        }
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <dias :usuario="{{$usuario}}" :p_dias="{{$dias}}" :p_semana="{{$semana}}" :maximo="{{$maximo}}"
                       :teorico="{{$teorico}}"></dias>
        </div>
    </div>

    <template id="dias-template">
        <div class="card">
            <div class="card-header">@{{ usuario.name+' '+semana }}</div>
            <div class="card-body">
                <div class="d-flex justify-content-between col-10 col-sm-6 m-auto">
                    <button v-if="semana>1" class="btn btn-sm btn-light" @click="mostrarSemana(semana-1, true)">
                        <i v-if="semana>1" class="fa fa-arrow-left"></i>
                        <i v-else></i>
                    </button>
                    <i v-else></i>
                    <select class="selectpicker" v-model="semana" @change="mostrarSemana(semana, false)">
                        <option v-for="s in p_semana" :value="s">Semana @{{ s }}</option>
                    </select>
                    <button v-if="maximo>(((semana - 1) * 7)+dias)" class="btn btn-sm btn-light" @click="mostrarSemana(semana+1, true)">
                        <i class="fa fa-arrow-right"></i>
                    </button>
                    <i v-else></i>
                </div>
                <hr>
                <div class="row justify-content-start">
                    <div v-for="dia in dias" class="card m-1" style="width: 15rem;">
                        <div class="card-header">
                            Dia: @{{ dia.dia }}
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <img class="link" :src="dia.imagen" width="100%"/>
                                <div v-if="dia.id!=null">
                                    <button class="btn btn-sm btn-light" @click="comentar(dia)">
                                        <i class="fa fa-comment"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light"  @click="mostrar(dia)">
                                        <i class="fa fa-image"></i>
                                    </button>
                                </div>
                            </div>
                            <div v-if="dia.comentar==1">
                                <span class="small">(@{{dia.comentario.length}}/255)</span>
                                <textarea class="form-control" @blur="agregarComentario(dia)" v-model="dia.comentario"></textarea>
                                <form-error v-if="dia.comentario.length>255" name="comentario" :errors="errors" ></form-error>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="dia.length==0" align="center" >
                    <h5>[No hay datos para mostrar]</h5>
                </div>
            </div>
            <modal ref="modalImagen" title="Imagen" :showok="false" :showcancel="false">
                <img :src="dia.imagen" width="100%">
            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>
        Vue.component('dias', {
            template: '#dias-template',
            props: ['usuario','p_dias','p_semana','maximo','teorico'],
            data: function () {
                return {
                    semana: 1,
                    ultimo: 1,
                    dias: [],
                    errors:[],
                    dia: {
                        comentarios: ''
                    },
                    loading: false,
                }
            },
            methods: {
                comentar: function (link, index) {
                    link.comentar = 1;
                    this.errors = [];
                },
                mostrar: function (link) {
                  this.link = link;
                  this.$refs.modalImagen.showModal();
                },
                agregarComentario: function (link) {
                    let vm = this;
                    vm.errors = [];

                    axios.post('{{url('/reto/comentar')}}', link).then(function (response) {
                        if (response.data.status == 'ok') {
                            link.comentar = 0;
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                    });
                },
                mostrarSemana: function (semana, actualizar) {
                    let vm = this;
                    axios.get('{{url('/usuarios/getSemana/')}}/' + this.usuario.id+'/'+semana).then(function (response) {
                        vm.dias = response.data;
                        if (actualizar){
                            vm.semana = semana;
                        }
                        Vue.nextTick(function () {
                            $('.selectpicker').selectpicker('refresh');
                        });
                    });
                }
            },
            created: function () {
                this.dias = this.p_dias;
                this.semana = this.p_semana;
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

@endsection
