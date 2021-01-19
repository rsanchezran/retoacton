@extends('layouts.welcome')
@section('header')
    <style>
        .formaPago {
            width: 50%;
            border: 1px solid gray;
            border-radius: 2px;
            padding: 10px;
            margin: 10px auto;
            align-content: center;
        }

        .payment {
            margin-right: 15px;
        }

        .payment input {
            margin: 10px;
        }

        .left {
            text-align: justify;
        }

        .imagen {
            border: 1px solid #6c757d;
            padding: 5px;
            margin: 5px;
        }

        .opps {
            border: 1px solid grey;
            width: 450px;
            margin: 10px auto;
            padding: 20px;
            text-align: justify;
            font-size: 12px;
        }

        .opps-reminder {
            padding: 9px 0 10px;
            font-size: 11px;
            text-transform: uppercase;
            text-align: center;
            color: #ffffff;
            background: #000000;
        }

        .opps-info {
            display: flex;
            align-content: center;
            text-align: center;
            padding: 20px;
        }

        .reference {
            text-align: center;
            padding: 6px 0 7px;
            border: 1px solid #b0afb5;
            border-radius: 4px;
            background: #f8f9fa;
        }

    </style>
@endsection
@section('content')

    <div id="vue" class="container flex-center">
        <registro :urls="{{$urls}}" :cobro="{{$cobro}}"></registro>
    </div>

    <template id="registro-template">
        <div class="container">
            <h4 class="acton">Bienvenido de nuevo al reto Acton de {{(int)(env('DIAS2')/7)}} semanas</h4>
            <br>
            <div>
                <h5 style="text-align: left">Realiza tu pago para seguir con los beneficios del reto Acton</h5>
                <div align="left">
                    <form-error name="nombre" :errors="errors"></form-error>
                    <div style="display: flex">
                        <input type="text" class="form-control" placeholder="Nombre" v-model="informacion.nombre">
                        <i v-if="informacion.nombre.length>2" class="fas fa-check-circle text-success"></i>
                    </div>
                    <form-error name="email" :errors="errors"></form-error>
                    <div style="display: flex">
                        <input type="email" class="form-control" placeholder="Correo electrónico"
                               v-model="informacion.email" @blur="saveContacto">
                        <i v-if="informacion.email.includes('@') && informacion.email.length>4" class="fas fa-check-circle text-success"></i>
                    </div>
                    <div style="display: flex">
                        <input type="text" class="form-control" placeholder="Teléfono"
                               v-model="informacion.telefono" @blur="saveContacto">
                        <i v-if="informacion.telefono.length>6" class="fas fa-check-circle text-success"></i>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <img v-for="url in urls" :src="url" width="200" class="imagen">
            </div>
            <hr>
            <div v-show="sent">
                <h5 style="text-align: left">Gracias! Ahora vamos a proceder al pago de tu suscripción para que comiences el Reto Acton
                    de {{env("DIAS")}} días</h5>
                <div class="formaPago" @click="metodoPago('openpay')">
                    Pago con tarjeta de débito
                    <br>
                    <i class="fab fa-cc-visa fa-3x"></i>
                    <i class="fab fa-cc-mastercard fa-3x"></i>
                </div>
                <div class="formaPago" @click="metodoPago('spei')">
                    Pago con SPEI
                    <br>
                    <img src="{{asset('img/spei.png')}}" width="80">
                </div>
                <div id="paypalDiv" class="formaPago">
                </div>
                <div class="formaPago" @click="metodoPago('oxxo')">
                    Pago en Oxxo
                    <br>
                    <img src="{{asset('img/oxxopay.jpg')}}" width="120">
                </div>
                <div class="formaPago" @click="metodoPago('deposito')">
                    Depósito o Transferencia
                    <br>
                    <img src="{{asset('images/imagesremodela/deposito.png')}}" width="120">
                </div>
                <hr>
            </div>
            <div v-if="response.referencia!=''">
                <button class="bigbutton" @click="$refs.referencia.showModal()">Ver ficha</button>
            </div>
            <hr>
            <modal ref="openpay" title="Pago con tarjeta" @ok="openpay" :high="'500'">
                <div>
                    <p>La cantidad a cobrar será de
                        <money :cantidad="'{{env('COBRO','0')}}'"></money>
                    </p>
                    <p>
                        <i class="fab fa-cc-visa fa-2x"></i>
                        <i class="fab fa-cc-mastercard fa-2x"></i>
                    </p>
                    <div class="payment" align="left" >
                        <input class="form-control" disabled v-model="informacion.nombre">
                        <input class="form-control" disabled v-model="informacion.email">
                        <input class="form-control" placeholder="Número de tarjeta" v-model="informacion.numero">
                        <form-error style="margin-left:10px;" name="numero" :errors="errors"></form-error>
                        <div align="left" style="margin-left:10px;" >
                        <span>¿Deseas utilizar esta tarjeta como medio de depósito a futuro? <input type="checkbox" v-model="informacion.deposito">
                            <br>(Podrás cambiar los datos más adelante) </span>
                        </div>
                        <input class="form-control" placeholder="Mes" v-model="informacion.mes">
                        <form-error style="margin-left:10px;" name="mes" :errors="errors"></form-error>
                        <input class="form-control" placeholder="Año" v-model="informacion.ano">
                        <form-error style="margin-left:10px;" name="ano" :errors="errors"></form-error>
                        <input class="form-control" placeholder="CVV" v-model="informacion.codigo">
                        <form-error style="margin-left:10px;" name="codigo" :errors="errors"></form-error>
                        <input type="hidden" v-model="informacion.token">
                        <input type="hidden" v-model="informacion.deviceSessionId">
                        <form-error name="tarjeta" :errors="errors"></form-error>
                    </div>
                </div>
            </modal>
            <modal ref="oxxo" :title="'Pago en oxxo'" @ok="OxxoSpei">
                <div class="payment">
                    <input class="form-control" disabled v-model="informacion.nombre">
                    <form-error name="nombre" :errors="errors"></form-error>
                    <input class="form-control" disabled v-model="informacion.email"/>
                    <form-error name="email" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Telefono" v-model="informacion.telefono"/>
                    <form-error name="telefono" :errors="errors"></form-error>
                    <form-error name="nombre" :errors="errors"></form-error>
                    <form-error name="email" :errors="errors"></form-error>
                </div>
            </modal>
            <modal ref="spei" :title="'Pago con SPEI'" @ok="OxxoSpei">
                <div class="payment">
                    <input class="form-control" disabled v-model="informacion.nombre"/>
                    <form-error name="nombre" disabled :errors="errors"></form-error>
                    <input class="form-control" v-model="informacion.email" disabled />
                    <form-error name="email" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Telefono" v-model="informacion.telefono"/>
                    <form-error name="telefono" :errors="errors"></form-error>
                </div>
            </modal>
            <modal ref="paypal" :title="'Pago con SPEI'" @ok="OxxoSpei">
                <div class="payment">
                    <input name="nombre" disabled class="form-control" v-model="informacion.nombre" />
                    <form-error name="nombre" :errors="errors"></form-error>
                    <input name="email" class="form-control" disabled v-model="informacion.email" />
                    <form-error name="email" :errors="errors"></form-error>
                    <input name="telefono" class="form-control" placeholder="Telefono" v-model="informacion.telefono" />
                    <form-error name="telefono" :errors="errors"></form-error>
                </div>
            </modal>
            <modal ref="deposito" :title="'Depósito o Transferencia'" @ok="deposito">
                <div class="opps">
                    <div class="opps-header">
                        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-ammount">
                                <h3>Monto a pagar</h3>
                                <h2>$ @{{this.pago}} <sup>MXN</sup></h2>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3>Referencia</h3>
                            <h1 class="reference">Banamex 5204 1653 0217 4390</</h1>
                            <h1 class="reference">HSBC 4213 1661 0039 0750</</h1>
                        </div>
                        <p>Este código es válido las siguientes 6 horas.</p>
                    </div>
                    <div class="opps-instructions">
                        <h3>Instrucciones</h3>
                        <ol>
                            <li>En esta opción de pago se hace el depósito a cualquiera de estas cuentas:
                            </li>
                            <li>Banamex 5204 1653 0217 4390</li>
                            <li>HSBC 4213 1661 0039 0750</li>


                            <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás
                                    verificar que se haya realizado correctamente.</strong> Conserva este comprobante de
                                pago para cualquier aclaración.
                            </li>

                            <li>Manda el comprobante ya sea por medio de WhatsApp (<a href="wa.link/b3peq4" target="_blank">4775581937</a>) o por correo (pagos@retoacton.com).</li>
                            <li>Anexando junto con tu comprobante:</li>
                            <li>-Nombre completo</li>
                            <li>-Correo electrónico</li>
                            <li>-Número de teléfono </li>

                        </ol>
                        <div class="opps-footnote">Y a la brevedad empezaremos el proceso de inscripción.
                        </div>
                    </div>
                </div>
            </modal>
            <modal ref="referencia" :title="'Ficha de pago'" @ok="urlLogin()" :cancelDisabled="true">
                <div class="opps">
                    <div class="opps-header">
                        <div class="opps-reminder">Ficha digital. No es necesario imprimir</div>
                        <div class="opps-info">
                            <div class="opps-brand">
                                <img v-if="response.origen=='oxxo'" src="{{asset('img/oxxo.png')}}" alt="oxxo" width="100">
                                <img v-if="response.origen=='spei'" src="{{asset('img/spei.png')}}" alt="oxxo" width="100">
                            </div>
                            <div class="opps-ammount">
                                <h3>Monto a pagar</h3>
                                <h2>$ @{{response.monto}} <sup>MXN</sup></h2>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3>Referencia</h3>
                            <h1 class="reference">@{{ response.referencia }}</h1>
                        </div>
                        <p>Este código es válido las siguientes 6 horas.</p>
                    </div>
                    <div class="opps-instructions">
                        <h3>Instrucciones</h3>
                        <ol>
                            <li v-if="response.origen=='oxxo'">Acude a la tienda OXXO de tu preferencia. <a
                                        href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala
                                    aquí</a>.
                            </li>
                            <li v-if="response.origen=='oxxo'">Indica en caja que quieres ralizar un pago de <strong>OXXOPay</strong>.</li>
                            <li v-if="response.origen=='oxxo'">Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la
                                pantalla de venta.
                            </li>
                            <li v-if="response.origen=='oxxo'">Realiza el pago correspondiente con dinero en efectivo.</li>
                            <li v-if="response.origen=='spei'">Accede a tu banca en línea.</li>
                            <li v-if="response.origen=='spei'">Da de alta la CLABE en esta ficha. El banco deberá de ser STP.</li>
                            <li v-if="response.origen=='spei'">Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, de lo
                                contrario se rechazará el cargo.
                            </li>
                            <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En el podrás
                                    verificar que se haya realizado correctamente.</strong> Conserva este comprobante de
                                pago para cualquier aclaración.
                            </li>
                        </ol>
                        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>contacto@grupoacton.com</strong>
                            confirmando tu pago.<br><br>Una vez efectuado el pago, inmediatamente llega tu programa
                            personalizado a tu correo, no es necesario enviar el comprobante de pago a ningún lado.
                        </div>
                    </div>
                </div>
            </modal>
        </div>
    </template>
@endsection
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id=AVweFu26ITOwsXSVPE7vFT0ZZEHjsOqLdbkgvOvZozo5pDjsAUfw5o3XAhPVrMihAwUWLMiV9R_N-0l7&currency=MXN"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay.v1.min.js"></script>
    <script src="https://openpay.s3.amazonaws.com/openpay-data.v1.min.js"></script>
    <script>

    </script>
    <script>
        Vue.component('registro', {
            template: '#registro-template',
            props: ['urls', 'cobro'],
            data: function () {
                return {
                    informacion: {
                        nombre: '',
                        email: '',
                        numero: '',
                        mes: '',
                        ano: '',
                        codigo: '',
                        deviceSessionId: '',
                        token: '',
                        telefono: '',
                        deposito: false
                    },
                    pago: '',
                    terminar: false,
                    sent: false,
                    errors: [],
                    response: {referencia: '', monto: '', origen:'oxxo'},
                }
            },
            methods: {
                metodoPago: function (pago) {
                    this.errors = [];
                    this.pago = "pago";
                    this.$refs[pago].showModal();
                    this.pago = pago;
                },
                openpay: function () {
                    let vm = this;

                    vm.errors = [];
                    vm.terminar = false;
                    OpenPay.token.create({
                            "holder_name": vm.informacion.nombre,
                            "card_number": vm.informacion.numero,
                            "cvv2": vm.informacion.codigo,
                            "expiration_month": vm.informacion.mes,
                            "expiration_year": vm.informacion.ano
                        },
                        function (response) {
                            vm.informacion.token = response.data.id;
                            axios.post('{{url('pago/openpay')}}', vm.informacion).then(function (response) {
                                if (response.data.status == 'ok') {
                                    vm.terminar = true;
                                    vm.$refs.openpay.closeModal();
                                    vm.$refs.openpay.working = false;
                                }
                                vm.$refs.openpay.working = false;
                            }).catch(function (errors) {
                                vm.errors = errors.response.data.errors;
                                vm.$refs.openpay.working = false;
                            });
                        },
                        function (error) {
                            vm.errors = {};
                            axios.post('{{url('pago/validarOpenpay')}}', vm.informacion)
                                .then()
                                .catch(function (errors) {
                                    vm.errors = errors.response.data.errors;
                                    vm.$refs.openpay.working = false;
                                });
                        }
                    );
                },
                OxxoSpei: function () {
                    let vm = this;
                    axios.post('{{url('pago/')}}'+'/'+vm.pago, vm.informacion)
                        .then(function (response) {
                            if (response.data.status == 'ok') {
                                vm.$refs.oxxo.closeModal();
                                vm.response.referencia = response.data.referencia;
                                vm.response.monto = response.data.monto;
                                vm.response.origen = response.data.origen;
                                vm.$refs.referencia.showModal();
                            }
                            vm.$refs.oxxo.working = false;
                        }).catch(function (errors) {
                        vm.errors = errors.response.data.errors;
                        vm.$refs[vm.pago].working = false;
                    });
                },
                deposito: function () {
                    let vm = this;
                    vm.$refs.deposito.closeModal();
                },
                urlLogin: function(){
                    window.location.href = 'login';
                },
                saveContacto: function () {
                    let vm = this;
                    vm.errors=[];
                    if(vm.informacion.nombre!='' &&  vm.informacion.email!='')
                        axios.post("{{url("saveContacto")}}", this.informacion)
                            .then(function (response) {
                                if (response.data.status = 'ok')
                                    vm.sent = true;
                            })
                            .catch(function (error) {
                                vm.sent = false;
                                vm.errors = error.response.data.errors;
                            });
                }
            },
            mounted: function () {
                let vm = this;
                OpenPay.setId('{{env('OPENPAY_ID')}}');
                OpenPay.setApiKey('{{env('OPENPAY_PUBLIC')}}');
                OpenPay.setSandboxMode(true);
                this.informacion.deviceSessionId = OpenPay.deviceData.setup("payment-form", "deviceIdHiddenFieldName");
                Vue.nextTick(function () {
                    document.getElementById('paypalDiv').innerHTML = "";
                    paypal.Buttons({
                        style: {
                            color: "blue",
                            layout: 'horizontal',
                            fundingicons: 'false',
                        },
                        funding: {
                            disallowed: [paypal.FUNDING.CREDIT, paypal.FUNDING.CARD]
                        },
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '10.00'
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            return actions.order.capture().then(function (details) {
                                axios.post('{{url('pago/paypal')}}', details.payer).then(function (response) {
                                    if (response.data.status == 'ok') {
                                        vm.terminar = true;
                                    }
                                }).catch(function (errors) {
                                    vm.errors = {tarjeta: ['Su tarjeta no es valida']};
                                });
                            });
                        }
                    }).render('#paypalDiv');
                });
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection