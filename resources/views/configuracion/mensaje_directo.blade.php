@extends('layouts.app')
@section('header')
    <style>
        .conversacion{
            background: #f2f2f2;
            background: #fff;
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
            background: transparent;
            color: black;
            border-radius: 10px;
            min-width: 3%;
            text-align: right;
            padding: 10px;
            border: 2px solid #0080DD;
        }
        .otro{
            float: left;
            background: #005B00;
            background: #cccccc;
            background: transparent;
            color: black;
            border-radius: 10px;
            min-width: 3%;
            text-align: right;
            padding: 10px;
            border: 2px solid #ccc;
        }
        .mensajes{

        }
        .box {
            height: auto;
            background-color: black;
            color: black;
            padding: 20px;
            position: relative;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .boxleft{
            background-color: #2e7d32;
            background-color: #CCCCCC;
            background-color: transparent;
            margin-left: 20px;
            border: 2px solid #2e7d32;
            border: 2px solid #CCCCCC;
        }
        .boxright{
            background-color: #00838f;
            background-color: transparent;
            margin-right: 20px;
            margin-left: 48% !important;
            text-align: right;
            border: 2px solid #0080DD;
        }

        .box.arrow-right:after {
            content: " ";
            position: absolute;
            right: -15px;
            top: -1px;
            border-top: 15px solid transparent;
            border-right: none;
            border-left: 15px solid #00838f;
            border-left: 15px solid #0080DD;
            border-bottom: 15px solid transparent;

            border-top: 0px solid transparent;
            border-right: none;
            border-left: 15px solid #00838f;
            border-left: 15px solid #0080DD;
            border-bottom: 15px solid transparent;
        }
        .box.arrow-left:after {
            content: " ";
            position: absolute;
            left: -15px;
            top: -1px;
            border-top: 15px solid transparent;
            border-right: 15px solid #2e7d32;
            border-right: 15px solid #CCCCCC;
            border-left: none;
            border-bottom: 15px solid transparent;

            border-top: 0px solid transparent;
            border-right: 15px solid #2e7d32;
            border-right: 15px solid #CCCCCC;
            border-left: none;
            border-bottom: 15px solid transparent;
        }
        .conversacion{
            max-height: 500px;
            overflow: scroll;
        }
        #txtmensaje{
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;

        }
        .input-group-text{
            border-radius: 80px !important;
            background: #0080DD !important;
            color:white;
            left: -14px;
            width: 36px;
            z-index: 99999999;
        }

        .image-upload>input {
            display: none;
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
        <div class="">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div class="w-100">
            <div class="conversacion" id="conversacion">
                <div v-for="p in this.mensajes_array[0]" class="mensajes">
                    <div v-if="p.usuario_emisor_id == aut" class="box arrow-right col-md-6 offset-6 boxright" v-html="p.mensaje"></div>
                    <div v-else class="box arrow-left col-md-6 boxleft" v-html="p.mensaje"></div>
                </div>
            </div>
            <div class="row col-12">
                    <div class="col-2">
                        <div class="image-upload">
                            <label for="file-input">
                                <img src="{{asset('images/2021/foto.png')}}" width="100%" class="mt-2">
                            </label>

                            <input id="file-input" type="file"  @change="loadTextFromFile"/>
                        </div>
                    </div>
                    <div class=" col-10 input-group mb-2">
                        <input type="text" id="txtmensaje" placeholder="Mensaje" class="form-control" v-model="mensaje">
                        <div class="input-group-prepend">
                            <div class="btn btn-primary input-group-text" @click="addMensaje()">></div>
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
                        $.each(response.data, function(key, val) {
                            if(val.mensaje.indexOf("data:image/") !== -1){
                                val.mensaje = "<img src='"+val.mensaje+"' width='150px'>";
                            }else{
                                val.mensaje = val.mensaje;
                            }
                        });
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
                        this.mensaje = '';
                        setTimeout(function(){
                            var objDiv = document.getElementById("conversacion");
                            objDiv.scrollTop = objDiv.scrollHeight;
                        }, 500);
                    }).catch(function (error) {
                        vm.errors = error.response;
                    });
                },
                loadTextFromFile(ev) {
                    let vm = this;
                    const file = ev.target.files[0];
                    const reader = new FileReader();

                    reader.readAsDataURL(ev.target.files[0])
                    setTimeout(function(){
                        console.log(reader.result);
                        axios.post('{{url('/configuracion/nuevo_mensaje')}}/140', {"mensaje": reader.result}).then((response) => {
                            this.getMensajes();
                            this.mensaje = '';
                            setTimeout(function(){
                                var objDiv = document.getElementById("conversacion");
                                objDiv.scrollTop = objDiv.scrollHeight;
                            }, 500);
                        }).catch(function (error) {
                            vm.errors = error.response;
                        });
                    }, 1000);
                },
            },
            mounted: function () {
                this.id = '{{$id}}';
                this.aut = '{{ Auth::user()->id }}';
                this.getMensajes();
                setTimeout(function(){
                    var objDiv = document.getElementById("conversacion");
                    objDiv.scrollTop = objDiv.scrollHeight;
                }, 500);
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
