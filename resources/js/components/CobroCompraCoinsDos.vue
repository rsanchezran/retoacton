
<template>
    <div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 d-block mr-auto ml-auto">
            <div class="d-flex flex-wrap">
                <div class="col-12 col-sm-6" @click="metodoPago('oxxo')">
                    <div class="formaPago row">
                        <div class="col-6">OXXO PAY</div>
                        <div class="col-6">
                            <img :src="url+'/img/oxxo.png'" width="50%">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6" @click="metodoPago('spei')">
                    <div class="formaPago row">
                        <div class="col-6">SPEI</div>
                        <div class="col-6">
                            <img :src="url+'/img/spei.png'" width="50%" class="">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6" @click="metodoPago('tarjeta')">
                    <div class="formaPago row">
                        <div class="col-6">Tarjeta de débito/crédito</div>
                        <div class="d-flex flex-wrap col-6">
                            <div class="col-6">
                                <img :src="url+'/img/visa.png'" width="20%">
                            </div>
                            <div class="col-6">
                                <img :src="url+'/img/mastercard.png'" width="20%">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6" @click="metodoPago('deposito')">
                    <div class="formaPago row">
                        <div class="col-6">Depósito</div>
                        <div class="col-6">
                            <img :src="url+'/images/imagesremodela/deposito.png'" width="120" />
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="formaPago">
                        <div>PAYPAL</div>
                        <br/>
                        <div id="paypalDiv" class="d-block ml-auto mr-auto" style="width:80%"></div>
                    </div>
                </div>
            </div>
            <div v-if="response.referencia!=''">
                <button class="bigbutton" @click="$refs.referencia.showModal()">Ver ficha</button>
            </div>

            <modal ref="deposito" :title="'Depósito o Transferencia'" @ok="deposito">
                <div class="opps">
                    <div class="opps-header">
                        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-ammount">
                                <h3>Monto a pagar</h3>
                                <money :caracter="true" :cantidad="cobro" :decimales="0"></money>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3>Referencia</h3>
                            <h2 class="reference">Banamex 5204 1653 0217 4390</h2>
                            <!--h1 class="reference">HSBC 4213 1661 0039 0750</h1-->
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

            <modal ref="tarjeta" title="Pago con tarjeta" @ok="tarjeta()" :high="'500'" :ok="!acuerdo">
                <div style="background-color: #f6f6f6; color: #0b2e13">
                    <div class="d-flex">
                        <div class="col-6">
                            <img :src="url+'/img/visa.png'" width="40">
                        </div>
                        <div class="col-6">
                            <img :src="url+'/img/mastercard.png'" width="40">
                        </div>
                    </div>
                    <p class="text-center">La cantidad a cobrar será de
                        <money :caracter="true" :cantidad="cobro" :decimales="0"></money>
                    </p>
                    <p>Al concluir tu pago se enviará tu usuario y contraseña al correo que proporcionaste en tus datos de
                        contacto</p>
                    <div class="payment" align="left">
                        <input class="form-control" v-model="informacion.nombres" placeholder="Nombres" />
                        <form-error name="nombres" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.apellidos" placeholder="Apellidos" />
                        <form-error name="apellidos" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email" placeholder="Correo electrónico" />
                        <form-error name="email" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email_confirmation"
                               placeholder="Por favor ingresa de nuevo tu correo electrónico"/>
                        <form-error name="email_confirmation" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.number" placeholder="Número de tarjeta">
                        <form-error name="numero" :errors="errors"></form-error>
                        <div class="d-flex">
                            <div class="col-sm-4">
                                <input class="form-control" placeholder="Mes" v-model="informacion.exp_month">
                                <form-error name="mes" :errors="errors"></form-error>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" placeholder="Año" v-model="informacion.exp_year">
                                <form-error name="ano" :errors="errors"></form-error>
                            </div>
                            <div class="col-sm-4">
                                <input class="form-control" placeholder="CVV" v-model="informacion.cvc">
                                <form-error name="codigo" :errors="errors"></form-error>
                            </div>
                        </div>
                        <div v-if="meses">
                            <input type="checkbox" id="meses" v-model="informacion.meses">
                            <label for="meses">3 meses sin intereses con tarjeta de crédito</label>
                        </div>
                        <input type="hidden" v-model="informacion.conektaTokenId">
                        <form-error name="tarjeta" :errors="errors" style="text-align: center"></form-error>
                    </div>
                    <div class="payment" align="left">
                        <input type="checkbox" id="acuerdoTarjeta" v-model="acuerdo">
                        <label for="acuerdoTarjeta">He leído y estoy de acuerdo con los
                            <a :href="url+'/terminos'" target="_blank">términos y condiciones</a>
                        </label>
                    </div>
                </div>
            </modal>
            <modal ref="oxxo" :title="'Pago en oxxo'" @ok="OxxoSpei" :ok="!acuerdo">
                <div style="background-color: #f6f6f6; color: #0b2e13">
                    <p class="text-center" style="font-size: 20px;">La cantidad a cobrar será de <money :caracter="true" :cantidad="cobro" :decimales="0"></money></p>
                    <p>Al concluir el ingreso de tus datos de contacto envíaremos a tu correo la ficha de déposito para que acudas a cualquier tienda Oxxo y hagas el pago correspondiente</p>
                    <p class="small">Si la ficha no llega a tu correo, porfavor revisa la bande de SPAM y agreganos como correo confiable</p>
                    <div class="payment">
                        <input class="form-control" v-model="informacion.nombres" placeholder="Nombre"  />
                        <form-error name="nombres" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.apellidos" placeholder="Apellidos"  />
                        <form-error name="apellidos" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email" placeholder="Correo electrónico"  />
                        <form-error name="email" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email_confirmation" placeholder="Por favor ingresa de nuevo tu correo electrónico"/>
                        <form-error name="email_confirmation" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.telefono" placeholder="Teléfono"/>
                        <form-error name="telefono" :errors="errors"></form-error>
                        <form-error name="codigo" :errors="errors"></form-error>
                    </div>
                    <div class="payment" align="left">
                        <input type="checkbox" id="acuerdoOxxo" v-model="acuerdo">
                        <label for="acuerdoOxxo">He leído y estoy de acuerdo con los
                            <a :href="url+'/terminos'" target="_blank">términos y condiciones</a>
                        </label>
                    </div>
                </div>
            </modal>
            <modal ref="spei" :title="'Pago con SPEI'" @ok="OxxoSpei" :ok="!acuerdo">
                <div style="background-color: #f6f6f6; color: #0b2e13">
                    <p class="text-center" style="font-size: 20px;">La cantidad a cobrar será de <money :caracter="true" :cantidad="cobro" :decimales="0"></money></p>
                    <p>Al concluir el ingreso de tus datos de contacto envíaremos a tu correo la ficha de déposito para que entres a tu banco en línea y hagas la transferencia a la cuenta CLABE proporcionada en esa ficha</p>
                    <p class="small">Si la ficha no llega a tu correo, porfavor revisa la bande de SPAM y agreganos como correo confiable</p>
                    <div class="payment">
                        <input class="form-control" v-model="informacion.nombres" placeholder="Nombres"  />
                        <form-error name="nombres" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.apellidos" placeholder="Apellidos"  />
                        <form-error name="apellidos" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email" placeholder="Correo electrónico"  />
                        <form-error name="email" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.email_confirmation" placeholder="Por favor ingresa de nuevo tu correo electrónico"/>
                        <form-error name="email_confirmation" :errors="errors"></form-error>
                        <input class="form-control" v-model="informacion.telefono" placeholder="Teléfono"/>
                        <form-error name="telefono" :errors="errors"></form-error>
                        <form-error name="codigo" :errors="errors"></form-error>
                    </div>
                    <div class="payment" align="left">
                        <input type="checkbox" id="acuerdoSpei" v-model="acuerdo">
                        <label for="acuerdoSpei">He leído y estoy de acuerdo con los
                            <a :href="url+'/terminos'" target="_blank">términos y condiciones</a>
                        </label>
                    </div>
                </div>
            </modal>
            <modal ref="referencia" :title="'Ficha de pago'" @ok="" :showcancel="false" :btncerrar="true">
                <div class="opps">
                    <div class="opps-header">
                        <div class="opps-reminder">Ficha digital. No es necesario imprimir.</div>
                        <div class="opps-info">
                            <div class="opps-brand">
                                <img v-if="response.origen=='oxxo'" :src="url+'/img/oxxo.png'" alt="oxxo" width="100">
                                <img v-if="response.origen=='spei'" :src="url+'/img/spei.png'" alt="spei" width="100">
                            </div>
                            <div class="opps-ammount">
                                <h3>Monto a pagar</h3>
                                <h2 id="monto"><money :cantidad="''+response.monto" :caracter="true" adicional=" MXN" :decimales="0"></money></h2>
                            </div>
                        </div>
                        <div class="opps-reference">
                            <h3>Referencia</h3>
                            <h1 class="reference">{{ response.referencia }}</h1>
                        </div>
                        <p>Este código es válido las siguientes 24 horas.</p>
                    </div>
                    <div class="opps-instructions">
                        <h3>Instrucciones</h3>
                        <ol>
                            <li v-if="response.origen=='oxxo'">Acude a la tienda OXXO de tu preferencia. <a
                                    href="https://www.google.com.mx/maps/search/oxxo/" target="_blank">Encuéntrala
                                aquí</a>.
                            </li>
                            <li v-if="response.origen=='oxxo'">
                                Indica en caja que quieres ralizar un pago de <strong>OXXOPay</strong>.
                            </li>
                            <li v-if="response.origen=='oxxo'">
                                Dicta al cajero el número de referencia en esta ficha para que tecleé directamete en la
                                pantalla de venta.
                            </li>
                            <li v-if="response.origen=='oxxo'">Realiza el pago correspondiente con dinero en efectivo.</li>
                            <li v-if="response.origen=='spei'">Accede a tu banca en línea.</li>
                            <li v-if="response.origen=='spei'">
                                Da de alta la CLABE en esta ficha. El banco deberá de ser STP.
                            </li>
                            <li v-if="response.origen=='spei'">
                                Realiza la transferencia correspondiente por la cantidad exacta en esta ficha, de lo
                                contrario se rechazará el cargo.
                            </li>
                            <li>Al confirmar tu pago, el cajero te entregará un comprobante impreso. <strong>En él podrás
                                verificar que se haya realizado correctamente.</strong> Conserva este comprobante de
                                pago para cualquier aclaración.
                            </li>
                        </ol>
                        <div class="opps-footnote">Al completar estos pasos recibirás un correo de <strong>soporte@retoacton.com</strong>
                            confirmando tu pago.<br><br>Una vez efectuado el pago, inmediatamente recibirás un correo con tu
                            usuario y contraseña para que puedas acceder a tu cuenta, no es necesario enviar el comprobante de pago a ningún lado.
                        </div>
                    </div>
                </div>
            </modal>
            <modal ref="pago_confirmado" :title="'Pago confirmado'" @ok="terminado" :showcancel="false" :btncerrar="false">
                <div>
                    <h3>Gracias por tu compra.</h3>
                    <p>
                        <span class="font-weight-bold">Felicidades! </span> Tu programa esta casi listo.
                    </p>
                    <p>Te hemos enviado un correo con tu usuario y contraseña para que puedas ingresar a tu sesión.
                        Recuerda que al ingresar por primera vez llenarás un cuestionario que te llevará aproximadamente 5 minutos.</p>
                    <p class="small">Si no ves el correo dentro de tu bandeja, por favor revisa tu carpeta SPAM y agreganos como un sitio de confianza</p>
                </div>
            </modal>
            <modal ref="pago_tarjeta" :title="'Pago con tarjeta'" @ok="terminado" :showcancel="false" :btncerrar="false">
                <div>
                    <h3>Gracias por tu compra.</h3>
                    <p>
                        <span class="font-weight-bold">Felicidades! </span> Tu programa esta casi listo.
                    </p>
                    <p>Estamos procesando tu pago, al terminar te enviaremos un correo con tu usuario y contraseña para que puedas ingresar a tu sesión.
                        Recuerda que al ingresar por primera vez llenarás un cuestionario que te llevará aproximadamente 5 minutos.</p>
                    <p class="small">Si no ves el correo dentro de tu bandeja, por favor revisa tu carpeta SPAM y agreganos como un sitio de confianza</p>
                </div>
            </modal>
            <modal ref="pagando" :title="'Aplicando pago con paypal'" :showok="false" :showcancel="false">
                <p>Estamos procesando tu pago con Paypal</p>
                <i v-if="loading" class="fa fa-spinner fa-spin"></i>
            </modal>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            cobro: {
                type: String,
                default: "0.0"
            },
            dias: {
                type: String,
                default: "0"
            },
            url: {
                type: String,
                default: "/"
            },
            id: {
                type: String,
                default: ''
            },
            sandbox:{
                type:Boolean,
                default: true
            },
            llave: {
                type: String,
                default: ''
            },
            meses:{
                type: Boolean,
                default: false
            }
        },
        data: function () {
            return {
                errors:{},
                acuerdo:false,
                loading:false,
                response: {referencia: '', monto: '', origen: 'oxxo'},
                informacion: {
                    name: '',
                    nombres: '',
                    apellidos: '',
                    email: '',
                    email_confirmation: '',
                    telefono: '',
                    codigo: '',
                    number: '',
                    exp_year: '',
                    exp_month: '',
                    cvc: '',
                    meses: false,
                    conektaTokenId: '',
                    deposito: false,
                    monto: this.cobro
                },
            }
        },
        methods: {
            metodoPago: function (pago) {
                this.errors = [];
                this.pago = "pago";
                this.$refs[pago].showModal();
                this.pago = pago;
            },
            limpiarInformacion: function(){
                this.informacion.nombres = this.informacion.nombres.trim();
                this.informacion.apellidos = this.informacion.apellidos.trim();
                this.informacion.email = this.informacion.email.trim();
                this.informacion.telefono = this.informacion.telefono.trim();
                this.informacion.number = this.informacion.number.trim();
                this.informacion.exp_year = this.informacion.exp_year.trim();
                this.informacion.exp_month = this.informacion.exp_month.trim();
                this.informacion.cvc = this.informacion.cvc.trim();
                this.informacion.name = '';
            },
            openpay: function () {
                let vm = this;
                vm.errors = {};
                vm.terminar = false;
            },
            OxxoSpei: function () {
                let vm = this;
                axios.post('/pago/' + vm.pago+'/coins', vm.informacion).then(function (response) {
                    if (response.data.status == 'ok') {
                        vm.$refs[vm.pago].closeModal();
                        vm.response.referencia = response.data.referencia;
                        vm.response.monto = response.data.monto;
                        vm.response.origen = response.data.origen;
                        vm.$refs.referencia.showModal();
                    }
                    vm.$refs[vm.pago].working = false;
                }).catch(function (errors) {
                    vm.errors = errors.response.data.errors;
                    vm.$refs[vm.pago].working = false;
                });
            },
            redirect: function () {
                window.location.href = '/login';
            },
            configurar: function (nombres, apellidos, email, telefono, codigo, monto) {
                this.informacion.nombres = nombres;
                this.informacion.apellidos = apellidos;
                this.informacion.email = email;
                this.informacion.telefono = telefono;
                this.informacion.codigo = codigo;
                this.informacion.monto = monto;
            },
            terminado: function () {
                this.$emit('terminado');
            },
            tarjeta: function () {
                let vm = this;
                vm.errors = {};
                vm.terminar = false;
                vm.informacion.name = vm.informacion.nombres+' '+vm.informacion.apellidos;
                Conekta.Token.create({card: this.informacion}, token => this.successConekta(token), error => this.errorConecta(error));
            },
            successConekta: function (token) {
                let vm = this;
                vm.informacion.conektaTokenId = token.id;
                axios.post(vm.url + '/pago/tarjeta'+'/coins', vm.informacion).then(function (respuesta) {
                    if (respuesta.data.status == 'ok') {
                        vm.$refs.tarjeta.closeModal();
                        vm.$refs.pago_tarjeta.showModal();

                    } else if (respuesta.data.status == 'error') {
                        if (respuesta.data.codigo == 3203) {
                            vm.errors = {tarjeta: ['Esta tarjeta no se puede utilizar a meses sin intereses']};
                        } else {
                            vm.errors = {tarjeta: [respuesta.data.error]};
                        }
                    }
                    vm.$refs.tarjeta.working = false;
                }).catch(function (errors) {
                    vm.errors = errors.response.data.errors;
                    vm.$refs.tarjeta.working = false;
                });
            },
            errorConecta: function (error) {
                let vm = this;
                vm.errors = {};
                vm.errors.tarjeta = [error.message_to_purchaser];
                vm.$refs.tarjeta.working = false;
            }
        },
        mounted() {
            let vm = this;

            setTimeout(function() {
                vm.informacion.nombres = vm.nombres;
                vm.informacion.email = vm.email;
                Conekta.setPublicKey(vm.llave);
                Vue.nextTick(function () {
                    document.getElementById('paypalDiv').innerHTML = "";
                    paypal.Buttons({
                        style: {
                            color: "silver",
                            layout: 'horizontal',
                            tagline: 'false',
                            shape: 'rect',
                            size: 'responsive'
                        },
                        funding: {
                            disallowed: [paypal.FUNDING.CREDIT, paypal.FUNDING.CARD]
                        },
                        createOrder: function (data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: vm.cobro
                                    }
                                }]
                            });
                        },
                        onApprove: function (data, actions) {
                            vm.loading = true;
                            vm.$refs.pagando.showModal();
                            return actions.order.capture().then(function (details) {
                                axios.post(vm.url + '/pago/paypal', vm.informacion).then(function (response) {
                                    if (response.data.status == 'ok') {
                                        vm.loading = false;
                                        vm.$refs.pagando.closeModal();
                                        vm.$refs.pago_confirmado.showModal();
                                    }
                                }).catch(function (errors) {
                                    vm.errors = {tarjeta: ['Su tarjeta no es valida']};
                                });
                            });
                        }
                    }).render('#paypalDiv');
                    $("#buttons-container").addClass(".buttons-container");
                });
            }, 1000);
        }
    };
</script>

<style scoped>
    .formaPago {
        border: 2px solid #e6e6e6;
        border-radius: 20px;
        padding: 10px;
        margin: 10px;
        align-content: center;
        text-align: center;
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
        text-align: left;
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

    @media only screen and (max-width: 420px) {
        .opps {
            width: 95%;
        }

        .reference{
            font-size:1.5rem;
        }

        #monto{
            font-size:1.2rem;
        }

        .formaPago h6{
            font-size: .8rem;
        }

        .formaPago img{
            width: 50px;
        }

    }
</style>
