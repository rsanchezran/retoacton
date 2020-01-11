@extends('layouts.welcome')
@section('header')
    <style>
        .big{
            font-size: 30px;
        }
    </style>
@endsection
@section('content')
    <div id="pago" class="container flex-center">
        <registro class="pt-5" :p_contacto="{{$contacto}}"></registro>
    </div>
    <template id="registro-template">
        <div class="container">
            <div id="header" align="center">
                <h6 class="text-uppercase bigText" >Bienvenido al</h6>
                <h6 class="text-uppercase biggerText font-weight-bold acton">Reto Acton</h6>
            </div>
            <div align="center">
                <h5>Porfavor compartenos que peso tienes actualmente y cual quieres lograr</h5>
                <div class="col-sm-8">
                    <input type="number" class="form-control col-sm-4" placeholder="Peso actual" v-model="contacto.peso">
                    <input type="number" class="form-control col-sm-4" placeholder="Peso ideal"
                           v-model="contacto.ideal" @blur="savePeso" @keyup.enter="savePeso">
                    <h5 v-if="alcanzable!=''">El peso que puedes alcanzar durante los primeros 30 días del reto es de</h5>
                    <h5 v-if="alcanzable!=''" class="biggestText acton font-weight-bold">@{{ alcanzable }} kg</h5>
                </div>
            </div>
            <div v-show="sent" align="center">
                <video autoplay src="{{url('/getVideo/peso ideal')}}/1" controls style="min-width: 95vmin; max-height: 20vmax;">
                    <source src="{{url('/getVideo/peso ideal')}}/1">
                </video>
                <hr>
                <div class="col-8 text-center d-block ml-auto mr-auto">
                    <h6 class="bigText">Gracias por compartirnos tus datos, el costo para unirte y tener los beneficios del <b class="acton">Reto Acton</b> es de</h6>
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
    </template>
@endsection
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id={{env('PAYPAL_SANDBOX_API_PASSWORD')}}&currency=MXN"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>

    <script>
        Vue.component('registro', {
            template: '#registro-template',
            props: ['urls', 'p_contacto'],
            data: function () {
                return {
                    errors: [],
                    sent: false,
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
                    if (this.contacto.peso !=null && this.contacto.peso != '' && this.contacto.peso > 0 &&
                        this.contacto.ideal!=null && this.contacto.ideal != '' && this.contacto.ideal > 0){
                        axios.post("{{url('/savePeso')}}", this.contacto).then(function (response) {
                            let alcanzable =parseInt(response.data);
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
            el: '#pago'
        });
    </script>
@endsection
