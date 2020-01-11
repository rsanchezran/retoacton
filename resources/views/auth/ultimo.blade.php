@extends('layouts.welcome')
@section('content')
    <div id="pago" class="container flex-center">
        <registro class="pt-5" :urls="{{$urls}}" :p_contacto="{{$contacto}}"></registro>
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
                <div class="d-block mr-auto ml-auto col-8">
                    <div class="col-8 text-center d-block ml-auto mr-auto">
                        <h6 class="bigText">Para unirte y tener los beneficios del <b class="acton">Reto Acton</b>el costo es de</h6>
                        <money id="cobro_anterior" cantidad="{{env("COBRO_ORIGINAL")}}" estilo="font-size:1.2em; color:#000000" :caracter="true"></money>
                        <h6 style="color: #000;">aprovecha el 50% de descuento ÚLTIMOS DIAS</h6>
                        <button id="pagar">a solo <money cantidad="{{env('COBRO')}}" :caracter="true" estilo="font-size:1.5em; font-weight: bold"></money></button>
                        <h6 style="color: #000;">Estas son las formas en que puedes hacer tu pago:</h6>
                        <br>
                        <cobro ref="cobro" :cobro="'{{env('COBRO')}}'" :url="'{{url('/')}}'" :id="'{{env('OPENPAY_ID')}}'"
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
            props: ['urls', 'p_pregunta','p_contacto'],
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
            el: '#pago'
        });
    </script>
@endsection
