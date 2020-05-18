<?php $__env->startSection('header'); ?>
    <style>
        .form-control {
            border: 0;
            border-radius: 0;
        }

        input.form-control {
            margin: 10px 0;
        }

        input[type="email"] {
            width: 100%;
        }

        a.btn-primary {
            font-size: 15pt;
            background-color: #1c4565;
            border-color: #1c4565;
            padding: 2% 20%;
        }

        a.btn-primary:hover {
            background-color: #2c628c;
        }

        #vue {
            background-color: #f2f2f2;
            background-image: url("<?php echo e(asset('img/rayogris.png')); ?>");
            background-repeat: no-repeat;
            background-position: center;
        }

        h6 {
            color: #929292;
        }

        @media  only screen and (max-width: 420px) {
            .container {
                margin-left: 0;
                margin-right: 0;
                padding-left: 5px;
                padding-right: 5px;
            }
        }

        .detalle{
            font-size: 1rem;
        }

        #pago{
            display: block;
            margin: auto
        }

        .paypal-buttons-context-iframe{
            min-width: 100% !important;
        }
        .acton{
            width: 200px;
            font-family: unitext_bold_cursive;
            padding: 10px;
            color:#FFF;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div id="vue" class="container flex-center">
        <registro class="pt-5" :urls="<?php echo e($urls); ?>" :medios="<?php echo e($medios); ?>"></registro>
    </div>

    <template id="registro-template">
        <div class="container">
            <div align="center">
                <div id="header" align="center">
                    <h6 class="text-uppercase bigText">Bienvenido</h6>
                    <br>
                    <br>
                </div>
                <h5 class="text-left" style="color:#0080DD">Antes de comenzar nos gustaría saber un poco más sobre
                    ti </h5>
                <select class="form-control" v-model="informacion.medio" @change="seleccionarMedio">
                    <option value="" disabled>¿Cómo te enteraste del reto acton?</option>
                    <option v-for="medio in medios" :value="medio">{{medio}}</option>
                </select>
                <div v-if="informacion.medio=='Por medio de un amigo'" class="text-left">
                    <span style="color: #929292">
                        Si conoces el código de referencia de tu amigo, por favor ingrésalo aquí
                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                    </span>
                    <input class="form-control col-6" v-model="informacion.codigo" placeholder="REFERENCIA"
                           @blur="buscarReferencia()" maxlength="7">
                    <form-error name="codigo" :errors="errors"></form-error>
                    <div v-if="encontrado!==null">
                        <span v-if="encontrado">El código que ingresaste corresponde al usuario :
                            <i style="font-size:1.1rem" class="font-weight-bold">{{ referencia }}</i>
                        </span>
                        <span v-else
                              class="font-weight-bold">[No se encontró al alguien con ese código de referencia]</span>
                    </div>
                </div>
                <div v-if="informacion.medio != ''" class="text-left">
                    <input class="form-control" placeholder="Nombres" v-model="informacion.nombres">
                    <form-error name="nombres" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Apellidos" v-model="informacion.apellidos">
                    <form-error name="apellidos" :errors="errors"></form-error>
                    <input class="form-control" placeholder="Teléfono" v-model="informacion.telefono">
                    <form-error name="telefono" :errors="errors"></form-error>
                    <input type="email" class="form-control" placeholder="Correo electrónico" v-model="informacion.email"
                    @blur="saveContacto" @keypress.enter="saveContacto">
                    <form-error name="email" :errors="errors"></form-error>
                    <div class="mt-4 text-left">
                        <button class="btn btn-primary acton" @click="saveContacto" :disabled="loading">
                            Continuar
                            <i v-if="loading" class="fa fa-spinner fa-spin"></i>
                            <i v-else class="fa fa-shopping-cart"></i>
                        </button>
                    </div>
                </div>
            </div>
            <br>
            <div v-show="sent" id="pago" class="text-center col-12">
                <div v-show="mensaje!=''">
                    <h6 class="detalle">{{ mensaje }}</h6>
                </div>
                <div v-show="mensaje==''">
                    <h6 class="detalle">¡Gracias por compartirnos tus datos,</h6>
                    <h6 class="detalle"> nos encantará ayudarte!</h6>
                    <h6 class="detalle"> El costo para unirte y tener los </h6>
                    <h6 class="detalle"> beneficios del <b class="text-uppercase">Reto Acton</b> es de:
                    </h6>
                    <label style="font-size: 1.4rem; font-family: unitext_bold_cursive">
                        <money v-if="descuento>0" id="cobro_anterior" :cantidad="''+original" :decimales="0"
                               estilo="font-size:1.2em; color:#000000" adicional=" MXN"
                               :caracter="true"></money>
                    </label>
                    <div id="infoPago" v-if="descuento>0">
                        <label style="font-size: 1rem; color: #000; font-family: unitext_bold_cursive">aprovecha
                            el </label>
                        <label style="font-size: 1.4rem; margin-top: -5px; font-family: unitext_bold_cursive">{{descuento }}% de descuento </label>
                        <label style="color: #000; font-weight: bold; font-family: unitext_bold_cursive" v-if="descuento=='<?php echo e(env('DESCUENTO')); ?>'">ÚLTIMO DIA</label>
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
                    <cobro ref="cobro" :cobro="''+monto" :url="'<?php echo e(url('/')); ?>'" :id="'<?php echo e(env('OPENPAY_ID')); ?>'"
                           :llave="'<?php echo e(env('CONEKTA_PUBLIC')); ?>'" :sandbox="'<?php echo e(env('SANDBOX')); ?>'==true" :meses="true"
                           @terminado="terminado"></cobro>
                </div>
            </div>
        </div>
    </template>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo e(env('PAYPAL_SANDBOX_API_PASSWORD')); ?>&currency=MXN"></script>
    <script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>

    <script>
        Vue.component('registro', {
            template: '#registro-template',
            props: ['urls', 'medios'],
            data: function () {
                return {
                    errors: [],
                    sent: false,
                    srcVideo: '',
                    informacion: {
                        nombres: '',
                        apellidos: '',
                        email: '',
                        telefono: '',
                        medio: '',
                        codigo: ''
                    },
                    loading: false,
                    encontrado: null,
                    referencia: '',
                    original: '0',
                    monto: '0',
                    descuento: '0',
                    mensaje: ''
                }
            },
            methods: {
                terminado: function () {
                    window.location.href = "<?php echo e(url('/login')); ?>";
                },
                buscarReferencia: function () {
                    let vm = this;
                    vm.referencia = '';
                    vm.loading = true;
                    axios.get('<?php echo e(url('buscarReferencia')); ?>/' + vm.informacion.codigo).then(function (response) {
                        vm.referencia = response.data.usuario;
                        vm.loading = false;
                        vm.encontrado = true;
                        if(vm.sent){
                            vm.saveContacto();
                        }
                    }).catch(function () {
                        if(vm.sent){
                            vm.saveContacto();
                        }
                        vm.loading = false;
                        vm.encontrado = false;
                    });
                },
                saveContacto: function () {
                    let vm = this;
                    this.loading = true;
                    this.errors = {};
                    this.informacion.nombres = this.informacion.nombres.trim();
                    this.informacion.apellidos = this.informacion.apellidos.trim();
                    this.informacion.email = this.informacion.email.trim();
                    this.informacion.telefono = this.informacion.telefono.trim();
                    this.informacion.codigo = this.informacion.codigo.trim();
                    if(this.informacion.nombres==''){
                        this.errors.nombres = ['El nombre es obligatorio'];
                    }
                    if(this.informacion.apellidos==''){
                        this.errors.apellidos = ['Los apellidos son obligatorios'];
                    }
                    if (this.informacion.telefono==''){
                        this.errors.telefono = ['El teléfono es obligatorio'];
                    }
                    if (this.informacion.email==''){
                        this.errors.email = ['El correo electrónico es obligatorio'];
                    }
                    if (Object.keys(this.errors).length == 0) {
                        axios.post("<?php echo e(url("saveContacto")); ?>", this.informacion).then(function (response) {
                            vm.sent = true;
                            vm.loading = false;
                            if (response.data.status == 'ok') {
                                vm.original = response.data.original;
                                vm.monto = response.data.monto;
                                vm.descuento = response.data.descuento;
                                vm.$refs.cobro.configurar(
                                    vm.informacion.nombres,
                                    vm.informacion.apellidos,
                                    vm.informacion.email,
                                    vm.informacion.telefono,
                                    vm.informacion.codigo,
                                );
                            }
                            vm.mensaje = response.data.mensaje;
                        }).catch(function (error) {
                            vm.sent = false;
                            vm.loading = false;
                            vm.errors = error.response.data.errors;
                        });
                    }else{
                        this.sent = false;
                        this.loading = false;
                    }
                },
                seleccionarMedio: function () {
                    this.informacion.codigo = '';
                    if(this.sent){
                        this.saveContacto();
                    }
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/register.blade.php ENDPATH**/ ?>