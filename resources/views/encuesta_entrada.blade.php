@extends('layouts.app')
@section('header')
    <style>
        .encuesta-leave-active { /*animacion para espiral activo con false */
            position: absolute;
            z-index: -1;
            /*animation: salida 1.3s;*/
        }

        @keyframes salida {
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

        @keyframes entrada {
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
            background-image: url("{{asset('/img/rayogris.png')}}");
            background-repeat: no-repeat;
            background-position: center;
        }

        @media only screen and (max-width: 420px) {
            .card-body{
                padding: 2px;
            }

            label.cuestionario {
                font-size: .68rem;
            }

            svg{
                width: 30px;
            }
            video{
                height: 300px !important;
            }
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <encuesta :p_preguntas="{{$preguntas}}" :urls="{{$urls}}"></encuesta>
        </div>
    </div>
    <template id="encuesta-template">
        <div>
            <div class="card">
                <div class="card-header" v-if="inicio.mostrar">
                    <div class="d-flex flex-wrap" style="padding: 20px">
                        <div class="col-12 col-sm-6" style="border-right: 1px solid #fff">
                            <span style="font-size: 1.2em; text-align: right">
                                <!-- PRIMER TITULO -->
                            </span>
                        </div>
                        <div class="col-12 col-sm-6 text-center" v-if="this.empieza">
                            <button class="btn btn-light text-uppercase font-weight-bold"
                                    style="margin-top: 20px; padding: 10px 80px; color:#007dd8;"
                                    @click="mostrarAbiertas()">Empezar
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="card-header text-center" style="padding: 20px; font-size: 1.2rem;">
                        @{{ pregunta }}
                </div>
                <div class="card-body" :style="inicio.mostrar?'padding:0':''">
                    <div v-if="!this.empieza || !this.quitavideo">
                        <video poster="/img/header.png" width="100%" height="500px" preload="none" style="object-fit: fill;" controls="controls" src="{{asset('/images/imagesremodela/crunch_con_soga.mp4')}}" class="embed-responsive-item" id="videoID" @ended="empieza=true">
                            <source src="{{asset('/images/imagesremodela/crunch_con_soga.mp4')}}" type="video/mp4">
                        </video>
                    </div>
                    <transition :name="mostrarEncuesta.animacion">
                        <div v-if="mostrarEncuesta.mostrar" class="col-sm-8 d-block mr-auto ml-auto">
                            <div v-for="(pregunta,key,index) in preguntasAbiertas">
                                <input v-if="key < 4" class="form-control encuesta" v-model="pregunta.respuesta"
                                       :placeholder="pregunta.pregunta" :id="pregunta.id">
                            </div>
                            <div v-if="errors_abierta" style="color:red;" id="errors_abierta">

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
                                    v-if="pregunta.multiple != undefined"> {{--animacion de la pantalla de css--}}

                            <div v-if="pregunta.mostrar">
                                <div class="d-block mr-auto ml-auto">
                                    <div class="form-group">
                                        <div class="pregunta"> {{--Preguntas con opciones--}}
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
                                                       :for="pregunta.id+''+indexR"> {{--texto pregunta--}}
                                                    @{{ opcion.respuesta }}
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
                            <div v-for="(pregunta,key,index) in preguntasAbiertas">
                                <textarea v-if="key == 2" class="form-control encuesta" v-model="pregunta.respuesta"
                                          :placeholder="pregunta.pregunta" rows="5"></textarea>
                            </div>
                            <div style="display: flex; justify-content: space-between">
                                <button class="siguiente" @click="termina">
                                    <i class="far fa-chevron-double-right"></i>
                                </button>
                            </div>
                            <span v-if="errorAbierta" style="color:red;">Completa la informaci??n</span>
                            <form-error name="siguiente" :errors="errors"></form-error>
                        </div>

                        <div v-if="continuar" align="center">
                                <video poster="/img/header.png" width="100%" height="500px" preload="none" style="object-fit: fill;" controls="controls" src="{{asset('/images/imagesremodela/crunch_con_soga.mp4')}}" class="embed-responsive-item" id="videoID" @ended="endedvideointermedio=true">
                                    <source src="{{asset('/images/imagesremodela/crunch_con_soga.mp4')}}" type="video/mp4">
                                </video>
                                <div v-if="endedvideointermedio" align="center">
                                <!--a class="btn btn-primary btn-md" href="{{url('/reto/dia/1/0/0')}}"-->
                                    <a class="btn btn-primary btn-md" href="{{url('/register')}}">
                                        <span>Continuar</span>
                                    </a>
                                </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </template>

@endsection
@section('scripts')

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
                        mostrar: false,
                        mostrarUltima: false
                    },
                    datosPersonales: {
                        animacion: 'encuesta',
                        mostrar: false
                    },
                    empieza: false,
                    quitavideo: false,
                    videointermedio: false,
                    endedvideointermedio: false,
                    num_pregunta: 0,
                    referencia: '',
                    user: {},
                    numero: 0,
                    terminar: false,
                    preguntasCerradas: [],
                    preguntasAbiertas: [],
                    usuarionoapto: [],
                    imostrarInicionformacion: '',
                    loading: false,
                    pago: '',
                    errors: [],
                    continuar: false,
                    pregunta: '',
                    errorAbierta: false,
                    nombre_: '',
                    apellidos_: '',
                    telefono_: '',
                    email_: '',
                    errors_abierta: false,
                }
            },
            methods: {
                comprobarAbiertas: function () {//comprueba errores con las preguntas (cerradas y abiertas)
                    let vm = this;
                    vm.errors = [];
                    if(document.getElementById("14").value != '' && document.getElementById("15").value != '' && document.getElementById("16").value != '' && document.getElementById("17").value != '' ){
                        this.nombre_ = document.getElementById("14").value;
                        this.apellidos_ = document.getElementById("15").value;
                        this.telefono_ = document.getElementById("16").value;
                        this.email_ = document.getElementById("17").value;
                        document.cookie = "nombre="+this.nombre_;
                        document.cookie = "apellidos="+this.apellidos_;
                        document.cookie = "telefono="+this.telefono_;
                        document.cookie = "email="+this.email_;
                        if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email_)) {
                            this.errors_abierta = false;
                            vm.mostrarCerradas();
                        }else{
                            this.errors_abierta = true;
                        }
                    }else{
                        this.errors_abierta = true;
                    }
                    setTimeout(function(){
                        if(this.errors_abierta ){
                            document.getElementById('errors_abierta').innerHTML = 'Completa los siguientes campos:';
                            if(document.getElementById("14").value == ''){
                                document.getElementById('errors_abierta').innerHTML += '<br>Nombre';
                            }
                            if(document.getElementById("15").value == ''){
                                document.getElementById('errors_abierta').innerHTML += '<br>Apellidos';
                            }
                            if(document.getElementById("16").value == ''){
                                document.getElementById('errors_abierta').innerHTML += '<br>Tel??fono';
                            }
                            if(document.getElementById("17").value == ''){
                                document.getElementById('errors_abierta').innerHTML += '<br>Correo electronico';
                            }else{
                                if(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("17").value)) {
                                    document.getElementById('errors_abierta').innerHTML += '<br>Correo electronico';
                                }
                            }
                        }
                    }, 100);

                },
                comprobarCerrada: function (pregunta, direccion) {
                    if (direccion == 1) {
                        let count = 0;
                        this.errors = [];
                        let findError = false;
                        if (pregunta.multiple == 0) {
                            for (let i = 0, opcion = pregunta.opciones; i < opcion.length && count == 0; i++) {
                                if (opcion[i].selected) {//buscar que almenos uno este seleccionado
                                    count++;
                                    if(pregunta.excluye !== null){
                                        if(pregunta.excluye.indexOf(i) != -1){
                                            this.usuarionoapto.push(pregunta.id);
                                            console.log(this.usuarionoapto);
                                        }
                                    }
                                }
                            }
                            if (count == 0 && pregunta.id != 10) {
                                findError = true;
                                this.errors.seleccion = ['Seleccione al menos una opci??n'];
                            }
                            if (count == 0 && pregunta.id == 10) {
                                this.usuarionoapto.push(pregunta.id);
                            }
                        }else{
                            var total = 0;
                            if(pregunta.id == 7){
                                total = 5;
                            }
                            var resp = 0;
                            for (var z = 0, opcion = pregunta.opciones; z < opcion.length && resp < total; z++) {
                                if (opcion[z].selected) {
                                    resp++;
                                }
                            }
                            if(resp != 0) {
                                if (resp < total) {
                                    findError = true;
                                    this.errors.seleccion = ['Debes seleccionar por lo menos 5'];
                                }
                            }else{
                                this.usuarionoapto.push(pregunta.id);
                            }
                        }
                        console.log(this.usuarionoapto);
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
                    this.quitavideo = true;
                    this.mostrarEncuesta.mostrar = true;
                    this.preguntasAbiertas.forEach(function (item, index) {
                        item.mostrar = true;
                    });
                    this.pregunta = "Por favor llena esta informaci??n";
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
                        this.pregunta = this.preguntasCerradas[vm.numero-1].pregunta;
                        this.num_pregunta = this.preguntasCerradas[vm.numero-1].id;
                    } else {
                        vm.terminar = true;
                        vm.pregunta = "A continuaci??n te mostrar?? como es que se desarrolla tu programa."
                        vm.continuar = true;
                        vm.terminar = false;
                        if(this.usuarionoapto.length == 0){
                            document.cookie = "ksdoi=dds";
                        }else{
                            document.cookie = "ksdoi=lls";
                        }
                        //vm.terminar = true;
                        //this.pregunta = "Por favor llena esta informaci??n";
                        //let respuestas = vm.preguntasAbiertas.concat(vm.preguntasCerradas);
                        /*axios.post("", {usuario: vm.user, respuestas: respuestas})
                            .then(function (respuesta) {
                                vm.terminar = true;
                                vm.pregunta = "Estamos casi listos..."
                            })
                            .catch(function (error) {
                                vm.terminar = false;
                            });*/
                    }
                },
                termina: function () { //muestra la siguiente pregunta y cierra la anteriror
                    var vm = this;
                    var respuestas = vm.preguntasAbiertas.concat(vm.preguntasCerradas);

                    vm.errorAbierta = true
                    vm.errors = [];
                    axios.post('{{url('encuesta/validarAbiertasdos')}}', vm.preguntasAbiertas)
                        .then(function (respuesta) {
                            vm.continuar = true;
                            axios.post("{{url('encuesta/save')}}", {usuario: vm.user, respuestas: respuestas})
                                .then(function (respuesta) {
                                    vm.continuar = true;
                                    vm.terminar = false;
                                    vm.pregunta = "Estamos casi listos..."
                                })
                                .catch(function (error) {
                                });
                        })
                        .catch(function (errors) {
                            vm.errors = errors.response.data.errors;
                            vm.errors['siguiente'] = ['Llene todos los campos']
                        });
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
                document.cookie = "ksdoi=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "nombre=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "apellidos=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "telefono=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                vm.p_preguntas.forEach(function (item) { //separar Preguntas Abiertas de Cerradas
                    if (item.multiple == undefined)
                        vm.preguntasAbiertas.push(item);
                    else
                        vm.preguntasCerradas.push(item);
                });
                vm.inicio.mostrar = true;
                console.log(vm.preguntasAbiertas);
            },

        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>

@endsection