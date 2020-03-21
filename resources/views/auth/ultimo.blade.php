@extends('layouts.welcome')
@section('header')
    <style>
        h6 {
            color: #929292;
        }

        #pago{
            display: block; margin: auto;
        }

        #vue{
            background-color: #f2f2f2;
            background-image: url("{{asset('img/rayogris.png')}}");
            background-repeat: no-repeat;
            background-position: center;
        }
        .paypal-buttons-context-iframe{
            min-width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div id="vue" class="container flex-center">
        <registro class="pt-5" :urls="{{$urls}}" :p_contacto="{{$contacto}}" :monto="'{{$monto}}'"
                  :descuento="'{{$descuento}}'" :original="{{$original}}" :mensaje="'{{$mensaje}}'"></registro>
    </div>
    <template id="registro-template">
        <div>
            <div align="center">
                <div id="header" align="center">
                    <h6 class="text-uppercase bigText" >Bienvenido al</h6>
                    <h6 class="text-uppercase biggerText font-weight-bold acton">Reto Acton</h6>
                </div>
                <video autoplay src="{{url('/getVideo/ultimo')}}/1" style="min-width: 95vmin; max-height: 20vmax;">
                    <source src="{{url('/getVideo/ultimo')}}/1">
                </video>
                <div id="pago" class="col-12 text-center" style="display: block; margin: auto">
                    <div v-show="mensaje!=''">
                        <h6 style="font-size: 1.7em">@{{ mensaje }}</h6>
                    </div>
                    <div v-show="mensaje==''">
                        <h6 class="bigText">Para unirte y tener los beneficios del <b class="acton">Reto Acton</b> el costo es de</h6>
                        <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                            <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                   estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                   :caracter="true"></money>
                        </label>
                        <div id="infoPago" v-if="descuento>0">
                            <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                                el </label>
                            <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{descuento }}% de descuento </label>
                            <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive" v-if="descuento=='{{env('DESCUENTO')}}'">ÚLTIMO DIA</label>
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
            </div>
        </div>
    </template>
@endsection
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>

    <script>
        Vue.component('registro', {
            template: '#registro-template',
            props: ['urls', 'p_pregunta','p_contacto','monto','descuento','original','mensaje'],
            data: function () {
                return {
                    errors: [],
                    terminar: false,
                    informacion: {
                        nombres: '',
                        apellidos:'',
                        email: '',
                        telefono: '',
                        medio: '',
                        codigo:''
                    },
                    contacto:{},
                }
            },
            methods: {
                terminado: function () {
                    window.location.href = "{{url('/login')}}";
                },
            },
            mounted: function () {
                this.contacto = this.p_contacto;
                this.informacion.nombres = this.contacto.nombres;
                this.informacion.apellidos = this.contacto.apellidos;
                this.informacion.email = this.contacto.email;
                this.$refs.cobro.configurar(
                    this.informacion.nombres,
                    this.informacion.apellidos,
                    this.informacion.email,
                    this.informacion.telefono,
                    this.informacion.pregunta,
                    this.informacion.codigo,
                    this.informacion.referenciado
                );
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
