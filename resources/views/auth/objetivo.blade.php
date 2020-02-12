@extends('layouts.welcome')
@section('header')
    <style>
        .scrolldown.animate-active{
            position: absolute;
            -webkit-animation: fadeIn 1s;
            animation: fadeIn 1s;
            position: static;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-30%);
            }
            100% {
                opacity: 1;
                transform: translateY(0);

            }
        }

        svg {
            border: 3px solid #ffa321;
            margin: 5px;
            width: 40px;
        }

        svg.spiral {
            border-radius: 50%;
        }

        .pregunta {
            display: flex;
            flex-wrap: wrap;
        }

        .respuesta {
            margin: 5px;
            width: 48%;
        }

        .card-b {
            border-bottom: 1px solid lightgray ;
        }

        h6 {
            color: #929292;
        }
    </style>
@endsection
@section('content')
    <div id="pago" class="container flex-center">
        <registro class="pt-5" :urls="{{$urls}}" :p_pregunta="{{$pregunta}}" :p_contacto="{{$contacto}}" :monto="'{{$monto}}'"
                  :descuento="'{{$descuento}}'" :original="'{{$original}}'" :mensaje="'{{$mensaje}}'"></registro>
    </div>
    <template id="registro-template">
        <div>
            <div id="header" align="center">
                <h6 class="text-uppercase bigText" >Bienvenido al</h6>
                <h6 class="text-uppercase biggerText font-weight-bold acton">Reto Acton</h6>
            </div>
            <div v-show="mostrarObjetivos" v-animate="'scrolldown'">
                <h4 class="text-uppercase">Elige tu @{{ pregunta.pregunta}}</h4>
                <div class="pregunta card-b">
                    <div class="respuesta" v-for="(respuesta, index) in pregunta.opciones">
                        <input :id="'respuesta'+index" type="checkbox" v-show="false" @change="selecRespuesta(index)"/>
                        <svg class="spiral" viewBox="0 0 100 100" @click="selecRespuesta(index)">
                            <circle v-if="respuesta.selected"  cx="50" cy="50" r="40" stroke="#0089d1" fill="#0089d1" />
                        </svg>
                        <label @click="selecRespuesta(index)">@{{ respuesta.nombre}}</label>
                    </div>
                </div>
            </div>
            <div v-show="srcVideo" align="center">
                <div class="card-b">
                    <br>
                    <video ref="rvideo" autoplay :src="'{{url('/getVideo')}}/'+srcVideo" width="90%">
                        <source :src="'{{url('/getVideo')}}/'+srcVideo">
                    </video>
                </div>
                <div class="d-flex col-12" style="display: block; margin: auto">
                    <div id="pago" class="col-12 text-center" style="display: block; margin: auto">
                        <div v-show="mensaje!=''">
                            <h6 style="font-size: 1.7em">@{{ mensaje }}</h6>
                        </div>
                        <div v-show="mensaje==''">
                            <h6 class="bigText">Para unirte y tener los beneficios del <b class="acton">Reto Acton</b> el costo es de</h6>
                            <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                                <money id="cobro_anterior" :cantidad="''+original" :decimales="0"
                                       estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                                       :caracter="true"></money>
                            </label>
                            <div id="infoPago" v-if="descuento>0">
                                <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha el </label>
                                <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">@{{ descuento }}% de descuento </label>
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
        props: ['urls', 'p_pregunta','p_contacto', 'monto', 'descuento','original','mensaje'],
        data: function () {
            return {
                errors: [],
                mostrarObjetivos: true,
                srcVideo: '',
                informacion: {
                    nombres: '',
                    apellidos:'',
                    email: '',
                    telefono: '',
                    medio: '',
                    codigo:''
                },
                pregunta:{},
                contacto:{},
            }
        },
        methods: {
            terminado: function () {
                window.location.href = "{{url('/login')}}";
            },
            selecRespuesta(index){
                let vm = this;
                let opcion = vm.pregunta.opciones;
                _.each(this.pregunta.opciones, function (opcion) {
                    opcion.selected = false;
                });
                this.pregunta.opciones[index].selected = true;
                vm.srcVideo = (opcion[index].nombre.includes('Bajar') ? 'objetivo%20bajar' : 'objetivo%20subir')+'/{{rand(1,10)}}';
                this.contacto.objetivo = (opcion[index].nombre.includes('Bajar') ? 'bajar' : 'subir');
                axios.post("{{url('/saveObjetivo')}}", this.contacto).then(function (response) {
                    vm.$refs.cobro.configurar(
                        vm.informacion.nombres,
                        vm.informacion.apellidos,
                        vm.informacion.email,
                        vm.informacion.telefono,
                        vm.informacion.pregunta,
                        vm.informacion.codigo,
                        vm.informacion.referenciado
                    );
                    vm.mostrarObjetivos = false;
                });
            },
        },
        mounted: function () {
            this.pregunta = this.p_pregunta;
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
