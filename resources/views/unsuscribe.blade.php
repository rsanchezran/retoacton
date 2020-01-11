@extends('layouts.welcome')
@section('content')
    <div id="vue">
        <inicio></inicio>
    </div>
    <template id="inicio-template">
        <div align="center">
            <h4>Gracias por mostrar interés en nosotros</h4>
            <h5>¿Deseas dejar de recibir noticias del RETO ACTON?</h5>
            <button class="btn btn-success">
                <i class="fa fa-sign-out"></i> Dejar de recibir noticias
            </button>
        </div>
    </template>
@endsection

@section('scripts')
    <script>
        Vue.component('inicio', {
            template: '#inicio-template',
            props:['contacto'],
            methods: {
                dejar: function () {
                    axios.post('{{url('unsuscribe')}}', this.contacto);
                }
            }
        });
        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
