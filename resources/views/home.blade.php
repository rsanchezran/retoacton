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
        <inicio :usuario="{{ $usuario}}" :referencias="{{$referencias}}" :monto="{{$monto}}" :descuento="{{$descuento}}"
        :original="{{$original}}"></inicio>
    </div>

    <template id="inicio-template">
        <div class="container">
            @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                <div class="card">
                    <div class="card-header">Reto concluido</div>
                    <div class="card-body">
                        <h6 style="font-size: 1.4em">Tu reto ha concluido, te invitamos a realizar tu pago para que sigas gozando los beneficios del Reto Acton</h6>
                        <hr>
                        <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                            <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                   estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                   :caracter="true"></money>
                        </label>
                        <div id="infoPago" v-if="descuento>0">
                            <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                                el </label>
                            <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                            <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive">ÚLTIMO DIA</label>
                        </div>
                        <div id="pagar">
                            <div>a solo</div>
                            <div style="font-size: 1.5rem; margin-left: 5px">
                                <money :cantidad="''+monto" :caracter="true" :decimales="0"
                                       estilo="font-size:1.5em; font-weight: bold"></money>
                            </div>
                        </div>
                        <br>
                        <h6 style="color: #000;">Estas son las formas de realizar tu pago de manera segura</h6>
                        <cobro ref="cobro" :cobro="''+monto" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
                               :llave="'{{env('OPENPAY_PUBLIC')}}'" :sandbox="'{{env('SANDBOX')}}'==true" :meses="true"
                               @terminado="terminado"></cobro>
                    </div>
                </div>
                <hr>
            @endif
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
                                <a v-else class="btn btn-lg btn-primary" href="{{url('/reto/programa')}}">
                                    <span>Mi programa</span>
                                </a>
                                <br>
                                <br>
                                <a href="{{asset('/assets/cuaderno.pdf')}}" target="_blank">
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
    @if(\Illuminate\Support\Facades\Auth::user()->vencido)
        <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
        <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
        <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    @endif
    <script>
        Vue.component('inicio', {
            template: '#inicio-template',
            props: ['usuario', 'referencias','monto','original','descuento'],
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
                terminado: function () {
                    window.location.href = "{{url('/home')}}";
                }
            },
            mounted: function () {
                this.filtros.referencia = this.usuario.referencia;
                this.buscar();
                @if(\Illuminate\Support\Facades\Auth::user()->vencido)
                    this.$refs.cobro.configurar(
                        this.usuario.name,
                        this.usuario.last_name,
                        this.usuario.email,
                        this.usuario.telefono,
                        this.usuario.codigo,
                        this.usuario.referenciado
                    );
                @endif
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
