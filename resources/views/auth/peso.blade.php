@extends('layouts.welcome')
@section('header')
    <style>
        .big{
            font-size: 30px;
        }

        .btn-info{
            padding: 10px;
            background-color: #1b4b72;
            color:#FFF;
            border-color: #1b4b72;
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
        h6 {
            color: #929292;
        }
        .paypal-buttons-context-iframe{
            min-width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div id="vue" class="container flex-center">
        <registro class="pt-5" :p_contacto="{{$contacto}}" :monto="'{{$monto}}'" :descuento="'{{$descuento}}'"
                  :original="{{$original}}" :mensaje="'{{$mensaje}}'">
        </registro>
    </div>
    <template id="registro-template">
        <div class="container">
            <div id="header" align="center">
                <h6 class="text-uppercase bigText" >Bienvenido al</h6>
                <br>
                <br>
            </div>
            <div v-show="sent" align="center">
                <video autoplay src="{{url('/getVideo/peso ideal')}}/1" controls style="min-width: 95vmin; max-height: 20vmax;">
                    <source src="{{url('/getVideo/peso ideal')}}/1">
                </video>
                <hr>
                <div id="pago" class="col-12 text-center">
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
                            <div>a sólo</div>
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
            <div v-show="!sent" align="center">
                <h5>Por favor compartenos que peso tienes actualmente y cual quieres lograr</h5>
                <div class="col-sm-8">
                    <input type="number" class="form-control col-sm-4" placeholder="Peso actual" v-model="contacto.peso">
                    <input type="number" class="form-control col-sm-4" placeholder="Peso ideal" v-model="contacto.ideal">
                    <button class="mt-2 btn btn-info" @click="savePeso" :disabled="(contacto.peso==''||contacto.ideal=='') || sending">
                        <i v-if="sending" class="fa fa-spinner fa-spin"></i>
                        <i v-else class="fa fa-calculator"></i> Calcular peso
                    </button>
                    <h5 v-if="alcanzable!=''">El peso que puedes alcanzar durante los primeros 30 días del reto es de</h5>
                    <h5 v-if="alcanzable!=''" class="biggestText acton font-weight-bold">@{{ alcanzable }} kg</h5>
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
            props: ['urls', 'p_contacto','monto','descuento','original', 'mensaje'],
            data: function () {
                return {
                    errors: [],
                    sent: false,
                    sending: false,
                    informacion: {
                        nombres: '',
                        apellidos:'',
                        email: '',
                        telefono: '',
                        medio: '',
                        codigo:''
                    },
                    alcanzable:'',
                    contacto:{},
                }
            },
            methods: {
                terminado: function () {
                    window.location.href = "{{url('/login')}}";
                },
                savePeso: function(){
                    let vm = this;
                    vm.sending = true;
                    if (this.contacto.peso !=null && this.contacto.peso != '' && this.contacto.peso > 0 &&
                        this.contacto.ideal!=null && this.contacto.ideal != '' && this.contacto.ideal > 0){
                        axios.post("{{url('/savePeso')}}", this.contacto).then(function (response) {
                            let alcanzable =parseInt(response.data);
                            vm.sending = false;
                            vm.sent = true;
                            vm.alcanzable = isNaN(alcanzable) ? vm.peso : alcanzable;
                            vm.$refs.cobro.configurar(
                                vm.informacion.nombres,
                                vm.informacion.apellidos,
                                vm.informacion.email,
                                vm.informacion.telefono,
                                vm.informacion.pregunta,
                                vm.informacion.codigo,
                                vm.informacion.referenciado
                            );
                        }).catch(function () {
                            vm.sending = false;
                            vm.alcanzable = vm.contacto.peso;
                        });
                    }
                },
            },
            mounted: function () {
                this.contacto = this.p_contacto;
                this.informacion.nombres = this.contacto.nombres;
                this.informacion.apellidos = this.contacto.apellidos;
                this.informacion.email = this.contacto.email;
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
