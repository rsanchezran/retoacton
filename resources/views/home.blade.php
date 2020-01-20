@extends('layouts.app')
@section('header')
    <style>
        .dash {
            margin: 10px;
            padding: 10px;
        }
        hr{
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .money{
            margin-left: 5px;
        }
    </style>
@endsection
@section('content')
    <div id="vue" class="container flex-center">
        <inicio :usuario="{{ $usuario}}" :referencias="{{$referencias}}"></inicio>
    </div>

    <template id="inicio-template">
        <div class="container">
            <div class="card">
                <div class="card-header">Hola, @{{ usuario.name }}</div>
                <div class="card-body" style="padding: 0">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div style="display: flex; flex-wrap:wrap;  background-color: #E9E9E9; padding: 20px">
                        <div class="col-12 col-sm-6" align="center" style="border:padding: 5px">
                            <img :src="'{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id.'/'.rand(0,10))}}'"
                                 width="100">
                            <h4>Código de referencia</h4>
                            <h4 class="acton">{{\Illuminate\Support\Facades\Auth::user()->referencia}}</h4>
                        </div>
                        <div class="col-12 col-sm-6 d-flex" style="align-items: flex-end;">
                            <div class="d-block ml-auto mr-auto text-center">
                                <h4>Saldo a favor</h4>
                                <h4 class="acton">$<money :cantidad="usuario.saldo"></money></h4>
                                <a v-if="usuario.inicio_reto==null" class="btn btn-lg btn-primary" href="{{url('/reto/cliente/')}}">
                                    <span>EMPEZAR RETO</span>
                                </a>
                                <a v-else class="btn btn-lg btn-primary" href="{{url('/reto/diario')}}">
                                    <span>Mi programa</span>
                                </a>
                                <br>
                                <br>
                                <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank" class="btn btn-ml btn-primary">
                                    <i class="fa fa-file-pdf"></i> Descarga aquí tu cuaderno de trabajo
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="dash">
                        <div class="table-responsive">
                            <div class="d-flex">
                                <div class="col-12 col-sm-6">
                                    <h6>Estas son las personas que han usado tu código de referencia: </h6>
                                </div>
                                <div class="col-12 col-sm-6 d-flex" style="justify-content:flex-end">
                                    <span class="badge badge-light money"><money :caracter="true" :cantidad="''+usuario.total"></money></span>
                                    <span class="badge badge-light money"><money :caracter="true" :cantidad="''+usuario.depositado"></money></span>
                                    <span class="badge badge-light money"><money :caracter="true" :cantidad="''+usuario.saldo"></money></span>
                                </div>
                            </div>
                            <table class="table" style="margin: 0px;">
                                <tr v-for="referencia in referenciados.data">
                                    <td>@{{ referencia.name }}</td>
                                    <td>@{{ referencia.email }}</td>
                                    <td><fecha :fecha="referencia.created_at"></fecha></td>
                                </tr>
                                <tr v-if="referencias.length==0">
                                    <td>[Todavía no se ha utilizado tu referencia]</td>
                                </tr>
                            </table>
                            <div class="float-right">
                                <paginador ref="paginador" :url="'{{url('/usuarios/referencias')}}'" @loaded="loaded"></paginador>
                                <br>
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
        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['usuario', 'referencias'],
            data: function(){
                return{
                referenciados: [],
                filtros:{
                    referencia: ''
                },
                buscando: false
            }},
            methods: {
                loaded: function (referencias) {
                    this.referenciados = referencias;
                },
                buscar: function () {
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
                    this.buscando = false;
                },
            },
            mounted: function () {
                this.filtros.referencia = this.usuario.referencia;
                this.buscar();
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
