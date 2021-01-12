@extends('layouts.app')
@section('header')
    <style>
        .conversacion{
            background: #f2f2f2;
            border-radius: 10px;
            max-height: 500px;
            min-height: 500px;
            margin-bottom: 20px;
            margin-top: 20px;
            padding: 20px;
            overflow: auto;
        }
        .yo{
            float: right;
            background: #002456;
            color: white;
            border-radius: 10px;
            min-width: 3%;
            text-align: right;
            padding: 10px;
        }
        .otro{
            float: left;
            background: #005B00;
            color: white;
            border-radius: 10px;
            min-width: 3%;
            text-align: right;
            padding: 10px;
        }
        .mensajes{

        }
        .box {
            height: auto;
            background-color: black;
            color: #fff;
            padding: 20px;
            position: relative;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .boxleft{
            background-color: #2e7d32;
            margin-left: 20px;
        }
        .boxright{
            background-color: #00838f;
            margin-right: 20px;
            margin-left: 48% !important;
            text-align: right;
        }

        .box.arrow-right:after {
            content: " ";
            position: absolute;
            right: -15px;
            top: 15px;
            border-top: 15px solid transparent;
            border-right: none;
            border-left: 15px solid #00838f;
            border-bottom: 15px solid transparent;
        }
        .box.arrow-left:after {
            content: " ";
            position: absolute;
            left: -15px;
            top: 15px;
            border-top: 15px solid transparent;
            border-right: 15px solid #2e7d32;
            border-left: none;
            border-bottom: 15px solid transparent;
        }
        .conversacion{
            max-height: 500px;
            overflow: scroll;
        }

        @media only screen and (max-width: 800px) {
            .boxleft {
                margin-left: 0px !important;
            }

            .boxright {
                margin-left: 0% !important;
            }
        }
    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="conversacion" id="conversacion">
            <div v-for="p in this.mensajes_array[0]" class="mensajes">
                <div v-if="p.usuario_emisor_id == aut" class="box arrow-right col-md-6 offset-6 boxright">@{{ p.mensaje }}</div>
                <div v-else class="box arrow-left col-md-6 boxleft">@{{ p.mensaje }}</div>
            </div>
            <div class="col-md-12">
                <div class="col-auto">
                    <div class="input-group mb-2">
                        <input type="text" id="txtmensaje" placeholder="Mensaje" class="form-control" v-model="mensaje">
                        <div class="input-group-prepend">
                            <div class="btn btn-primary input-group-text" @click="addMensaje()">Enviar</div>
                        </div>
                    </div>
                </div>
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
                    mensajes_array: [],
                    id: 0,
                    aut: 0,
                    mensaje: '',
                    scrollr: true
                }
            },
            methods: {
                getMensajes: function () {
                    let vm = this;
                    axios.post('{{url('/configuracion/conversacion')}}/'+this.id).then((response) => {
                        this.mensajes_array.splice(0);
                        this.mensajes_array.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                addMensaje: function(){
                    let vm = this;
                    axios.post('{{url('/configuracion/nuevo_mensaje')}}/'+this.id, {"mensaje": this.mensaje}).then((response) => {
                        this.getMensajes();
                    }).catch(function (error) {
                        vm.errors = error.response;
                    });
                }
            },
            mounted: function () {
                this.id = '{{$id}}';
                this.aut = '{{ Auth::user()->id }}';
                this.getMensajes();
                setTimeout(function(){
                    var objDiv = document.getElementById("conversacion");
                    objDiv.scrollTop = objDiv.scrollHeight;
                }, 1000);
                setInterval(() => {
                    this.getMensajes();
                }, 2000)
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
