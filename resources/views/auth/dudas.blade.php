@extends('layouts.app')
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
        <contacto class="pt-5" :p_contacto="{{$contacto}}"></contacto>
    </div>

    <template id="contacto-template">
        <div class="container">
            <label>¿Qué dudas tienes?</label>
            <div>
                <label>Mensaje<span class="small">(@{{ contacto.mensaje.length }}/500)</span></label>
                <textarea class="form-control" cols="30" rows="5" v-model="contacto.mensaje"></textarea>
                <form-error name="mensaje" :errors="errors"></form-error>
            </div>
            <br>
            <div class="d-flex flex-column">
                <div>
                    <captcha ref="captcha" sitekey="{{env('CAPTCHA_PUBLIC')}}" @verify="onVerify" :loadRecaptchaScript="true">
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
            props: ['p_contacto'],
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
                    axios.post('{{url('dudas')}}', this.contacto).then(function () {
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
