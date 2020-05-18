<?php $__env->startSection('header'); ?>
    <style>
        .encuesta-leave-active { /*animacion para espiral activo con false */
            position: absolute;
            z-index: -1;
            /*animation: salida 1.3s;*/
        }

        @keyframes  salida {
            0% {
                transform: translateY(0%);
            }
            50% {
                transform: translateY(-100%);
            }
            70% {
                transform: translateY(-80%);
            }
            100% {
                transform: translateY(-400%);
            }
        }

        .encuesta-enter-active { /*animacion para spiral activo cuando es true*/
            animation: entrada 1s;
        }

        @keyframes  entrada {
            0% {
                transform: translateX(400%);
            }
            100% {
                transform: translateX(0%);
            }
        }


        @-webkit-keyframes spiral {
            from {
                stroke-dashoffset: 588;
            }
            to {
                stroke-dashoffset: 0;
            }
        }

        .vertical-enter-active { /*Animacion para rayado horizontal*/
            stroke-dasharray: 1009.6, 1009.6;
            -webkit-animation: vertical 0.5s linear;
        }

        @-webkit-keyframes vertical {
            from {
                stroke-dashoffset: 1000;
            }
            to {
                stroke-dashoffset: 0;
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

        svg.vertical {
            border-radius: 0%;
        }

        .respuesta svg, .respuesta label {
            float: left;
            cursor: pointer;
        }

        .respuesta label {
            margin: 8px;
        }

        .siguiente {
            color: red;
            background: none;
            border: none;
        }

        .tarjeta input {
            margin: 5px;
        }

        label.cuestionario {
            font-size: 1rem;
        }

        input.form-control {
            margin: 10px 0;
        }

        input.form-control.encuesta {
            padding-bottom: 30px;
            padding-top: 30px;
            font-size: 13pt;
        }

        .siguiente, .anterior {
            color: #1b4b72;
            background: none;
            border: none;
            font-size: 1.5em;
            display: block;
            margin: 10px auto;
        }

        .pregunta {
            display: flex;
            flex-wrap: wrap;
        }

        .respuesta{
            font-size: .8rem;
        }

        a.btn-primary {
            font-size: 18pt;
            background-color: #f90;
            border-color: #f90;
            padding: 2% 20%;
        }

        a.btn-primary:hover {
            background-color: #f90;
        }

        .card-body {
            background-color: #f6f6f6;
            background-image: url("<?php echo e(asset('/img/rayogris.png')); ?>");
            background-repeat: no-repeat;
            background-position: center;
        }

        @media  only screen and (max-width: 420px) {
            .card-body{
                padding: 2px;
            }

            label.cuestionario {
                font-size: .68rem;
            }

            svg{
                width: 30px;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div id="vue">
        <div class="container">
            <encuesta :p_preguntas="<?php echo e($preguntas); ?>" :urls="<?php echo e($urls); ?>" :p_user="<?php echo e($user); ?>"></encuesta>
        </div>
    </div>
    <template id="encuesta-template">
        <div>
            <div class="card">
                <div class="card-header" v-if="inicio.mostrar">
                    <div class="d-flex flex-wrap" style="padding: 20px">
                        <div class="col-12 col-sm-6" style="border-right: 1px solid #fff">
                            <span style="font-size: 1.2em; text-align: right">
                                Empezaremos con un breve cuestionario que nos permitirá identificar qué tipo de programa
                                se adapta mejor a tus características físicas y objetivo.
                            </span>
                        </div>
                        <div class="col-12 col-sm-6 text-center">
                            <button class="btn btn-light text-uppercase font-weight-bold"
                                    style="margin-top: 20px; padding: 10px 80px; color:#007dd8;"
                                    @click="mostrarAbiertas()">Empezar
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="card-header text-center" style="padding: 20px; font-size: 1.2rem;">
                    {{ pregunta }}
                </div>
                <div class="card-body" :style="inicio.mostrar?'padding:0':''">
                    <div v-if="inicio.mostrar">
                        <img src="<?php echo e(asset('img/encuesta.jpg')); ?>" width="100%">
                    </div>
                    <transition :name="mostrarEncuesta.animacion">
                        <div v-if="mostrarEncuesta.mostrar" class="col-sm-8 d-block mr-auto ml-auto">
                            <div v-for="(pregunta) in preguntasAbiertas">
                                <input class="form-control encuesta" v-model="pregunta.respuesta"
                                       :placeholder="pregunta.pregunta">
                                <form-error align="left" :name="pregunta.pregunta+'.respuesta'"
                                            :errors="errors"></form-error>
                            </div>
                            <div style="display: flex; justify-content: space-between">
                                <button class="siguiente" @click="comprobarAbiertas()">
                                    <i class="far fa-chevron-double-right"></i>
                                </button>
                            </div>
                            <form-error name="siguiente" :errors="errors"></form-error>
                        </div>
                    </transition>
                    <div class="flex-row" v-for="(pregunta, indexP) in preguntasCerradas">
                        <transition name="encuesta"
                                    v-if="pregunta.multiple != undefined"> 
                            <div v-if="pregunta.mostrar">
                                <div class="d-block mr-auto ml-auto">
                                    <div class="form-group">
                                        <div class="pregunta"> 
                                            <div class="col-12 col-sm-6" v-for="(opcion, indexR) in pregunta.opciones">
                                                <input :id="pregunta.id+''+indexR" type="checkbox" v-show="false"
                                                       v-model="opcion.selected"
                                                       @change="seleccionar(pregunta, opcion)">
                                                <svg v-if="pregunta.multiple == 0" class="spiral" viewBox="0 0 100 100"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     @click="select(pregunta, opcion)">
                                                        <circle v-if="opcion.selected"  cx="50" cy="50" r="40" stroke="#0089d1" fill="#0089d1" />
                                                </svg>
                                                <svg v-else class="vertical" viewBox="0 0 100 100"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     @click="select(pregunta, opcion)">
                                                    <rect v-if="opcion.selected" x="10" y="10"  width="80" height="80" stroke="#0089d1" fill="#0089d1" />
                                                </svg>
                                                <label class="cuestionario"
                                                       :for="pregunta.id+''+indexR"> 
                                                    {{ opcion.respuesta }}
                                                </label>
                                            </div>
                                            <form-error name="seleccion" :errors="errors"></form-error>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button v-if="!terminar" class="anterior"
                                                @click="comprobarCerrada(pregunta, 0)">
                                            <i class="far fa-chevron-double-left"></i>
                                        </button>
                                        <button v-if="!terminar" class="siguiente"
                                                @click="comprobarCerrada(pregunta, 1)">
                                            <i class="far fa-chevron-double-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </transition>
                    </div>
                    <transition name="encuesta">
                        <div v-if="terminar" align="center">
                            <video @ended="continuar=true" controls autoplay width="90%" poster="<?php echo e(asset('/img/poster.png')); ?>">
                                <source src="<?php echo e(url('/getVideo/registro').'/'.rand(1,1000)); ?>" type="video/mp4">
                            </video>
                            <br>
                            <span>Estamos generando tu programa , en lo que terminamos te invitamos a ver este video de bienvenida</span>
                            <br>
                            <div v-if="continuar">
                                <?php if(\Illuminate\Support\Facades\Auth::user()->rol==\App\Code\RolUsuario::CLIENTE): ?>
                                    <a class="btn btn-primary btn-md" href="<?php echo e(url('/reto/programa')); ?>">
                                        <span>Ver plan</span>
                                    </a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-md" href="<?php echo e(url('/reto/dia/1/0/0')); ?>">
                                        <span>Ver plan</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </template>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>

    <script>
        Vue.component('encuesta', {
            template: '#encuesta-template',
            props: ['p_preguntas', 'urls', 'p_user'],
            data: function () {
                return {
                    inicio: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    mostrarEncuesta: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    datosPersonales: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    referencia: '',
                    user: {},
                    numero: 0,
                    terminar: false,
                    preguntasCerradas: [],
                    preguntasAbiertas: [],
                    imostrarInicionformacion: '',
                    loading: false,
                    pago: '',
                    errors: [],
                    continuar: false,
                    pregunta: ''
                }
            },
            methods: {
                comprobarAbiertas: function () {//comprueba errores con las preguntas (cerradas y abiertas)
                    let vm = this;
                    vm.errors = [];
                    axios.post('<?php echo e(url('encuesta/validarAbiertas')); ?>', vm.preguntasAbiertas)
                        .then(function (respuesta) {
                            vm.mostrarCerradas();
                        })
                        .catch(function (errors) {
                            vm.errors = errors.response.data.errors;
                            vm.errors['siguiente'] = ['Llene todos los campos']
                        });
                },
                comprobarCerrada: function (pregunta, direccion) {
                    if (direccion == 1) {
                        let count = 0;
                        this.errors = [];
                        let findError = false;

                        if (pregunta.multiple == 0) {
                            for (let i = 0, opcion = pregunta.opciones; i < opcion.length && count == 0; i++) {
                                if (opcion[i].selected)//buscar que almenos uno este seleccionado
                                    count++;
                            }
                            if (count == 0) {
                                findError = true;
                                this.errors.seleccion = ['Seleccione al menos una opción'];
                            }
                        }
                        if (!findError) {
                            this.siguienteCerrada(pregunta);
                        }
                    } else {
                        this.anteriorCerrada(pregunta);
                    }
                },
                mostrarDatosPersonales: function () {
                    this.mostrarEncuesta.mostrar = false;
                    this.inicio.mostrar = true;
                },
                mostrarAbiertas: function () { //muestra la siguiente pantalla inicio con solo preguntasAbiertas
                    this.inicio.mostrar = false;
                    this.mostrarEncuesta.mostrar = true;
                    this.preguntasAbiertas.forEach(function (item) {
                        item.mostrar = true;
                    });
                    this.pregunta = "Por favor llena esta información";
                },
                mostrarCerradas: function () { //muestra las primera preguntas de preguntasCerradas y oculta las preguntasAbiertas
                    this.numero = 0;
                    this.mostrarEncuesta.mostrar = false;
                    this.preguntasAbiertas.forEach(function (item, index) {
                        item.mostrar = false;
                    });
                    if (this.preguntasCerradas.length != 0) {
                        this.pregunta = this.preguntasCerradas[this.numero].pregunta
                        this.preguntasCerradas[this.numero++].mostrar = true;
                    }
                },
                siguienteCerrada: function (pregunta) { //muestra la siguiente pregunta y cierra la anteriror
                    let vm = this;
                    pregunta.mostrar = false;
                    if (vm.preguntasCerradas.length != vm.numero) {
                        this.preguntasCerradas[vm.numero].mostrar = true;
                        vm.numero++;
                        this.pregunta = this.preguntasCerradas[vm.numero-1].pregunta
                    } else {
                        let respuestas = vm.preguntasAbiertas.concat(vm.preguntasCerradas);
                        axios.post("<?php echo e(url('encuesta/save')); ?>", {usuario: vm.user, respuestas: respuestas})
                            .then(function (respuesta) {
                                vm.terminar = true;
                                vm.pregunta = "Estamos casi listos..."
                            })
                            .catch(function (error) {
                                vm.terminar = false;
                            });
                    }
                },
                anteriorCerrada: function (pregunta) {
                    pregunta.mostrar = false;
                    this.numero--;
                    if (this.numero > 0) {
                        this.preguntasCerradas[this.numero-1].mostrar = true;
                        this.pregunta = this.preguntasCerradas[this.numero-1].pregunta
                    } else {
                        this.mostrarAbiertas();
                    }
                },
                select: function (pregunta, opcion) {
                    opcion.selected = !opcion.selected;
                    this.seleccionar(pregunta, opcion);
                },
                seleccionar: function (pregunta, opcion) { //seleccionar solo una opcion
                    if (pregunta.multiple == 0) {
                        _.each(pregunta.opciones, function (opciones) { //volver todas las no seleccionadas false
                            if (opciones.respuesta != opcion.respuesta)
                                opciones.selected = false;
                        })
                        pregunta.respuesta = opcion.respuesta;
                    } else {
                        const index = pregunta.respuesta.indexOf(opcion.respuesta);
                        let respuesta = pregunta.respuesta;
                        //agregar respuesta si no esta en el arreglo,  si esta la respuesta entonces quitala
                        (index == -1 ? respuesta.push(opcion.respuesta) : respuesta.splice(index, 1));
                    }
                },
            },
            created: function () {
                let vm = this;
                vm.user = vm.p_user;
                vm.p_preguntas.forEach(function (item) { //separar Preguntas Abiertas de Cerradas
                    if (item.multiple == undefined)
                        vm.preguntasAbiertas.push(item);
                    else
                        vm.preguntasCerradas.push(item);
                });
                vm.inicio.mostrar = true;
            },

        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/encuesta.blade.php ENDPATH**/ ?>