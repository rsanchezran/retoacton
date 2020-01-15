@extends('layouts.app')
@section('header')
    <style>
        #vue img {
            cursor: pointer;
        }
    </style>
@endsection
@section('content')

    <div id="vue">
        <div class="container">
            <temp-todo :p_links="{{$links}}" :usuario="{{$usuario}}"></temp-todo>
        </div>
    </div>

    <template id="temp">
        <div class="card">
            <div class="card-header">@{{ usuario.name }}</div>
            <div class="card-body">
                <div class="row justify-content-start">
                    <div v-for="(link, index) in links" class="card m-1" style="width: 15rem;">
                        <div class="card-header">
                            Dia: @{{ index+1 }}
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <img :src="link.imagen" width="100%"/>
                                <div>
                                    <button class="btn btn-sm btn-light" @click="comentar(link)">
                                        <i class="fa fa-comment"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light"  @click="mostrar(link)">
                                        <i class="fa fa-image"></i>
                                    </button>
                                </div>
                            </div>
                            <div v-if="link.comentar==1">
                                <span class="small">(@{{link.comentario.length}}/255)</span>
                                <textarea class="form-control" @blur="agregarComentario(link)" v-model="link.comentario"></textarea>
                                <form-error v-if="link.comentario.length>255" name="comentario" :errors="errors" ></form-error>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="links.length==0" align="center" >
                    <h5>[No hay datos para mostrar]</h5>
                </div>
            </div>
            <modal ref="modalImagen" title="Imagen">
                <img :src="link.imagen" width="100%">
            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>
        Vue.component('temp-todo', {
            template: '#temp',
            props: ['p_links', 'usuario'],
            data: function () {
                return {
                    errors:[],
                    links: [],
                    link:{},
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
                }
            },
            created: function () {
                this.links = this.p_links;
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

@endsection
