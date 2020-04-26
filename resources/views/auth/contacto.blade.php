@extends('layouts.welcome')
@section('header')
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

        h6, label {
            color: #929292;
        }

        .btn-info {
            background-color: #2C4574;
            border: 0;
            color: #FFF;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-info:disabled {
            background-color: #437393;
        }
    </style>
@endsection
@section('content')
    <div id="pago" class="container flex-center">
        <contacto class="pt-5" :p_contacto="{{$contacto}}" :objetivos="{{$objetivos}}"></contacto>
    </div>

    <template id="contacto-template">
        <div class="container">
            <div id="header" align="center">
                <h6 class="text-uppercase" style="font-size: 1.5em">Bienvenido al reto</h6>
                <h6 class="text-uppercase font-weight-bold" style="color: #013451; font-size: 2em">Acton de {{(int)(env('DIAS')/7)}} semanas</h6>
            </div>
            <div>
                <label>Nombres <span class="small">(@{{ contacto.nombres.length }}/100)</span></label>
                <input type="text" class="form-control" v-model="contacto.nombres">
                <form-error name="nombres" :errors="errors"></form-error>
            </div>
            <div>
                <label>Apellidos <span class="small">(@{{ contacto.apellidos.length }}/100)</span></label>
                <input type="text" class="form-control" v-model="contacto.apellidos">
                <form-error name="apellidos" :errors="errors"></form-error>
            </div>
            <div>
                <label>Correo electrónico <span class="small">(@{{ contacto.email.length }}/100)</span></label>
                <input type="text" class="form-control" v-model="contacto.email">
                <form-error name="email" :errors="errors"></form-error>
            </div>
            <div>
                <label>Teléfono <span class="small">(@{{ contacto.telefono.length }}/10)</span></label>
                <input type="text" class="form-control" v-model="contacto.telefono">
                <form-error name="telefono" :errors="errors"></form-error>
            </div>
            <div>
                <label>Objetivo fitness</label>
                <select class="selectpicker form-control" v-model="contacto.objetivo" data-title="Selecciona tu objetivo">
                    <option value="" disabled></option>
                    <option v-for="objetivo in objetivos" :value="objetivo">@{{ objetivo }}</option>
                </select>
            </div>
            <div>
                <label>Mensaje<span class="small">(@{{ contacto.mensaje.length }}/500)</span></label>
                <textarea class="form-control" cols="30" rows="5" v-model="contacto.mensaje"></textarea>
                <form-error name="mensaje" :errors="errors"></form-error>
            </div>
            <br>
            <div class="d-flex flex-column">
                <div>
                    <captcha ref="captcha" sitekey="{{env('CAPTCHA_PUBLIC')}}" @verify="onVerify">
                    </captcha>
                    <form-error name="captcha" :errors="errors"></form-error>
                </div>
                <div class="mt-2 mb-2">
                    <button class="btn btn-info" @click="enviar" :disabled="!ready">
                        <i class="fa fa-paper-plane"></i> Enviar
                    </button>
                </div>
            </div>
            <modal ref="modal" title="Gracias por contactarnos" @ok="salir" @cancel="salir" :showcancel="false">
                <p>Daremos respuesta a tu solicitud en las próximas 24 horas.</p>
            </modal>
        </div>
    </template>
@endsection
@section('scripts')

    <script>
        Vue.component('contacto', {
            template: '#contacto-template',
            props: ['p_contacto', 'objetivos'],
            data: function () {
                return {
                    errors: {},
                    contacto: {
                        response: ''
                    },
                    loading: false,
                    ready: false,
                }
            },
            methods: {
                enviar: function () {
                    let vm = this;
                    vm.loading = true;
                    axios.post('{{url('contacto')}}', this.contacto).then(function () {
                        vm.$refs.modal.showModal();
                        vm.loading = false;
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                },
                onVerify: function (response) {
                    this.contacto.response = response;
                    this.ready = true;
                },
                salir: function () {
                    window.location.href = '{{url('/')}}';
                },
            },
            created:function () {
                this.contacto = this.p_contacto;
            }
        });

        var vue = new Vue({
            el: '#pago'
        });
    </script>
@endsection
