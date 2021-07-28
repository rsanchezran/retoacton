@extends('layouts.app')
@section('header')
    <style>
        .link{
            height: 200px;
        }

        .activo{
            border:2px solid #0b2e13;
        }

        .activo .card-header{
            background-color: #0b2e13;
        }
        .comentariousuario {
            border: 1px solid white;
            padding: 10px 10px 10px 30px;
            background: #dcdcdc;
            border-radius: 40px;
            margin-top: 20px;
        }
        .comentariosCaja{
            max-height: 250px;
            overflow: auto;
        }
        .likes{
            color: blue;
            margin-left: 10%;
            font-size: 18px;
            cursor: pointer;
        }
        .likescero{
            color: #c2c2c2;
        }

        .gal {
            -webkit-column-count: 4; /* Chrome, Safari, Opera */
            -moz-column-count: 4; /* Firefox */
            column-count: 4;
        }
        .gal img{ width: 100%; padding: 7px 0;}
        .gal video{ width: 100%; padding: 7px 0;}
        @media (max-width: 500px) {

            .gal {


                -webkit-column-count: 1; /* Chrome, Safari, Opera */
                -moz-column-count: 1; /* Firefox */
                column-count: 1;


            }

        }
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <dias :usuario="{{$usuario}}" :p_dias="{{$dias}}" :p_semana="{{$semana}}" :maximo="{{$maximo}}" :p_dia="{{$dia}}"
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
                <div class="row">
                    <div class="gal">
                    <span v-for="dia in dias" style="width: 15rem;">
                                    <img class="link" :src="dia.imagen" @click="mostrar(dia)"/>

                                    <video  v-if="dia.id!=null" width="200" height="140"  :src="dia.video" @click="mostrarVideo(dia)">
                                        Tu navegador no soporta esta funcion.
                                    </video>
                            <div v-if="dia.comentar==1">
                                <span class="small">(@{{dia.comentario.length}}/255)</span>
                                <textarea class="form-control" @blur="agregarComentario(dia)" v-model="dia.comentario"></textarea>
                                <form-error v-if="dia.comentario.length>255" name="comentario" :errors="errors" ></form-error>
                            </div>
                    </span>
                    </div>
                </div>
                <div v-if="dia.length==0" align="center" >
                    <h5>[No hay datos para mostrar]</h5>
                </div>
            </div>
            <modal ref="modalImagen" title="Imagen" :showok="false" :showcancel="false">

                <div v-if="bandera">
                <img :src="dia.imagen" width="100%">
                <div v-if="likescount>0" class="likes" @click="likes(dia)">@{{ likescount }} <i class="fas fa-bolt"></i> (Likes)</div>
                <div v-if="likescount==0" class="likes likescero" @click="likes(dia)">@{{ likescount }} <i class="fas fa-bolt"></i> (Likes)</div>
<br>
                    <div class="comentariosCaja">
                        <div class="row col-md-12">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Comenta" v-model="comentario_nuevo" @keyup.enter="comenta(dia)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" @click="comenta(dia)"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                        <div v-for="p in this.posts[0]" class="comentariousuario">
                            <div class="nombrecomenta">@{{ p.usuario_comenta.name }}</div>
                            <div class="comentariounico">@{{ p.comentario }}</div>
                        </div>
                    </div>
                </div>

            </modal>
            <modal ref="modalVideo" title="Imagen" :showok="false" :showcancel="false">

                <div v-if="bandera">
                <video  v-if="dia.id!=null" width="480" controls  :src="dia.video">
                    Tu navegador no soporta esta funcion.
                </video>
                <div v-if="likescount>0" class="likes" @click="likes(dia)">@{{ likescount }} <i class="fas fa-bolt"></i> (Likes)</div>
                <div v-if="likescount==0" class="likes likescero" @click="likes(dia)">@{{ likescount }} <i class="fas fa-bolt"></i> (Likes)</div>
<br>
                    <div class="comentariosCaja">
                        <div class="row col-md-12">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Comenta" v-model="comentario_nuevo" @keyup.enter="comenta(dia)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-primary" type="button" @click="comenta(dia)"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                        <div v-for="p in this.posts[0]" class="comentariousuario">
                            <div class="nombrecomenta">@{{ p.usuario_comenta.name }}</div>
                            <div class="comentariounico">@{{ p.comentario }}</div>
                        </div>
                    </div>
                </div>

            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>
        Vue.component('dias', {
            template: '#dias-template',
            props: ['usuario','p_dias','p_semana','maximo','teorico','p_dia'],
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
                    posts: [],
                    bandera: false,
                    likescount: 0,
                    comentario_nuevo: ''
                }
            },
            methods: {
                comentar: function (dia, index) {
                    dia.comentar = 1;
                    this.errors = [];
                },
                mostrar: function (dia) {
                    let vm = this;
                    vm.errors = [];
                    this.dia = dia;
                    var datas = [];
                    var aa = this.$refs;
                    this.posts = [];
                    aa.modalImagen.showModal();
                    this.bandera = true;

                    axios.post('{{url('/usuarios/likes')}}/'+dia.dia+'/'+this.usuario.id)
                        .then((response) => {
                            this.likescount = response.data;
                        }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });

                    axios.post('{{url('/usuarios/comentarios')}}/'+dia.dia+'/'+this.usuario.id)
                        .then((response) => {
                            this.posts.push(response.data);
                        }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });

                },
                mostrarVideo: function (dia) {
                    let vm = this;
                    vm.errors = [];
                    this.dia = dia;
                    var datas = [];
                    var aa = this.$refs;
                    this.posts = [];
                    aa.modalVideo.showModal();
                    this.bandera = true;

                    axios.post('{{url('/usuarios/likes')}}/'+dia.dia+'/'+this.usuario.id)
                        .then((response) => {
                            this.likescount = response.data;
                        }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });

                    axios.post('{{url('/usuarios/comentarios')}}/'+dia.dia+'/'+this.usuario.id)
                        .then((response) => {
                            this.posts.push(response.data);
                        }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });

                },
                comenta: function(dia){
                    axios.post('{{url('/usuarios/comentario_nuevo')}}/'+dia.dia+'/'+this.usuario.id, {comentario: this.comentario_nuevo})
                        .then((response) => {
                            this.posts = [];
                            this.posts.push(response.data);
                            this.comentario_nuevo = '';
                        }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                likes: function (dia) {
                    axios.post('{{url('/usuarios/setlikes')}}/'+dia.dia+'/'+this.usuario.id)
                    .then((response) => {
                        this.likescount = response.data;
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });

                },
                agregarComentario: function (dia) {
                    let vm = this;
                    vm.errors = [];

                    axios.post('{{url('/reto/comentar')}}', dia).then(function (response) {
                        if (response.data.status == 'ok') {
                            dia.comentar = 0;
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                    });
                },
                comentarios: function (dia) {
                    let vm = this;
                    vm.errors = [];

                    axios.post('{{url('/usuarios/comentarios')}}/'+dia+'/'+this.usuario.id).then(function (response) {
                        Vue.set(this, 'post', response.data)
                        console.log(this.post);
                        this.bandera = true;
                        console.log(this.bandera);
                        //this.$set(this, 'comentarios', response.data);
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
                this.posts = [];
                this.dias = this.p_dias;
                this.semana = this.p_semana;
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

@endsection
