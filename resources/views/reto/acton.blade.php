@extends('layouts.app')
@section('content')

<div id="vue" >
    <div class="container">
        <tem-acton class="pb-sm-5" :datos_reto="{{$datos_reto}}"></tem-acton>
    </div>
</div>

<template id="temp">
    <div class="card">
        <div class="card-header"><i class="far fa-photo"></i> Fotos</div>
        <div class="card-body">
            <div class="d-flex">
                <div v-for="(dato, index) in datos"  class="card m-2" :style="'width: 15rem;'+(dato.subir?'':'background-color:#E0E0E0;')" >
                    <div class="card-header" style="padding-bottom: 1.5%; padding-top: 1.5%;">
                        Dia: @{{ index+1 }}
                    </div>
                    <div class="card-body" >
                        <div class="input-group" v-if="dato.subir" >
                            <div class="custom-file">
                                <input :ref="'imgNueva'+index" type="file" accept="image/jpg" class="custom-file-input"
                                      :disabled="dato.disabled" @change="agregarImagen(index, $refs)">
                                <label class="custom-file-label text-truncate">
                                    @{{ dato.nombre }}
                                </label>
                            </div>
                        </div>
                        <div v-else >
                            <img class="img-fluid" :src="linkImagen(dato.imagen)" />
                        </div>
                    </div>
                    <div v-if="!dato.disabled" class="row justify-content-center">
                        <div class="pb-4" >
                            <button class="btn btn-success" :disabled="activarButton" @click="save(index)" >
                                Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

@endsection
@section('scripts')
    <script>

        Vue.component('tem-acton',{
            template: '#temp',
            props: ['dias_activo','datos_reto'],
            data: function () {
                return {
                    files: [{
                        imagen: '',
                        dia:''
                    }],
                    datos: this.datos_reto
                }
            },
            methods: {
                agregarImagen: function (index, ref) {
                    let file =this.files[0];
                    console.log(file);
                    file.imagen = ref['imgNueva' + index][0].files[0];
                    file.dia = (index + 1);
                    this.datos[index].nombre = file.imagen.name;
                },
                linkImagen: function (link) {
                    return window.location.origin + link;
                },
                save: function (index) {
                    let vm = this;
                    let imagen = new FormData();
                    let buscar = vm.files[0];

                    imagen.append('imagen', buscar.imagen);
                    imagen.append('dia', JSON.stringify(buscar.dia));

                    vm.files = [{
                        imagen: '',
                        dia: ''
                    }];
                    axios.post("{{url('/reto/saveActon')}}", imagen)
                        .then(function (response) {
                            if (vm.datos[index + 1] != undefined) {
                                vm.datos[index + 1].disabled = false;
                                vm.datos[index + 1].subir = true;
                            }
                            if (response.data.respuesta == 'ok') {
                                vm.datos[index].disabled = true;
                                vm.datos[index].subir = false;
                            }
                        })
                        .catch(function (error) {
                        });
                }
            },
            computed:{
                activarButton: function () {
                    let file = this.files[0].imagen;
                    return (file ? false: true);
                }
            }
        });

        var vue= new Vue({
            el:'#vue'
        });
    </script>
@endsection