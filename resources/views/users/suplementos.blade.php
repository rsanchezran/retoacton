@extends('layouts.app_suplementos')
@section('header')
    <style>


        @media only screen and (max-width: 800px) {

        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="w-100 row" style="margin-top: 35% !important;margin-left: 0%; position: relative">
            <div class="col-6">
                <a href="/usuarios/fichas/maximal"><img src="{{asset('images/2021/maximal_ficha.png')}}" width="100%"></a>
            </div>
            <div class="col-6">
                <a href="/usuarios/fichas/ergogen"><img src="{{asset('images/2021/ergo_gen_ficha.png')}}" width="100%"></a>
            </div>
            <div class="col-6" style="margin-top: 4%">
                <a href="/usuarios/fichas/glutamina"><img src="{{asset('images/2021/glutamina_ficha.png')}}" width="100%"></a>
            </div>
            <div class="col-6" style="margin-top: 4%">
                <a href="/usuarios/fichas/bcaa"><img src="{{asset('images/2021/bcaa_ficha.png')}}" width="100%"></a>
            </div>
            <div class="col-6" style="margin-top: -5%">
                <a href="/usuarios/fichas/whey"><img src="{{asset('images/2021/whey_ficha.png')}}" width="100%"></a>
            </div>
            <div class="col-6 mb-5" style="margin-top: -5%">
                <a href="/usuarios/fichas/creatina"><img src="{{asset('images/2021/creatina_ficha.png')}}" width="100%"></a>
            </div>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('temp-retos', {
            template: '#temp',
            data: function () {
                return {
                    id: 0,
                    aut: 0,
                    mensaje: '',
                    scrollr: true
                }
            },
            methods: {

            },
            mounted: function () {
                this.aut = '{{ Auth::user()->id }}';
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
