@extends('layouts.app')
@section('header')
    <style>
        #repo {
            position: fixed;
            top: 10px;
            right: 1px;
            width: 400px;
            height: 95%;
            z-index: 1051;
            overflow: scroll;
            background-color: #FFF;
            border-radius: 5px 0 0 5px;
            border: 5px solid black;
            border-right: 2px;
        }

        .serie{
            background-color:  #ebf5fb;
            padding: 8px;
        }

        .ejercicio {
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            /*display: flex;*/
        }

        video {
            width: 400px;
            height: 200px;
        }

        textarea {
            height: 90% !important;
        }

        .static-sticky {
            z-index: 100;
            background: white;
            position: sticky;
            padding-bottom: 5px;
            top: 0px;
            box-shadow: 0px 12px 10px -10px darkgrey;
        }

        .vid {
            padding: 10px;
            color: grey;
            border: 1px solid #cdd3d9;
            height: 90%;
            border-radius: 4px;
            width:95%;
            height:200px;
        }

        .tab-content {
            padding: 15px;
        }

        .repeticion{
            width: 120px;
            margin: 2px;
        }

    </style>
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <programa :p_dia="{{$dia}}" :usuario="{{$usuario}}" :configuraciones="{{$configuraciones}}"
                      :genero="'{{$genero}}'" :objetivo="'{{$objetivo}}'"></programa>
        </div>
        <repo-explorer></repo-explorer>
    </div>

    <template id="programa-template">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <span>@{{(genero==0?'Hombre':'Mujer')}} @{{(objetivo==0?'Bajar':'Subir')}}, día @{{ dia.dia }}</span>
                    <a href="{{url('/configuracion/programa/')}}" class="btn btn-sm btn-light">
                        <i class="fa fa-backward"></i> Regresar
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs ml-2" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#objetivo" role="tab"
                           aria-selected="true">Notas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#ejercicios" role="tab"
                           aria-selected="false">Ejercicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#cardio" role="tab" aria-selected="false">Cardio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#copia" role="tab" aria-selected="false">Copiar
                            ejercicios</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active card" id="objetivo" role="tabpanel">
                        <div class="card-header">
                            <span>Notas de este día</span>
                        </div>
                        <div class="card-body">
                            <span>Anota aquí lo que quieras decirle al cliente en este día</span>
                            <div>
                                <span class="small float-right">@{{ dia.nota.length }}/255</span>
                                <textarea v-model="dia.nota" class="form-control"></textarea>
                            </div>
                            <form-error name="copia" :errors="errors"></form-error>
                            <form-error name="nota" :errors="errors"></form-error>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="ejercicios" role="tabpanel">
                        <div class="card-header">
                            <span>Ejercicios del día @{{ dia.dia }}</span>
                        </div>
                        <div class="d-flex" style="padding: 10px">
                            <ul class="nav nav-tabs col-sm-10" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#gym" role="tab"
                                       aria-selected="true">GYM</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#casa" role="tab" aria-selected="false">Casa</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="gym" role="tabpanel">
                                <lugar :title="'Ejercicios para GYM'" :lugar="dia.gym" :errors="errors" :genero="genero"
                                       :objetivo="objetivo" :modo="'gym'"></lugar>
                            </div>
                            <div class="tab-pane fade " id="casa" role="tabpanel">
                                <lugar :title="'Ejercicios para casa'" :lugar="dia.casa" :errors="errors" :genero="genero"
                                       :objetivo="objetivo" :modo="'casa'"></lugar>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="cardio" role="tabpanel">
                        <div class="card-header">
                            <span>Cardio del día @{{ dia.dia }}</span>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-sm btn-default float-right" @click="agregarCardio">
                                <i class="fa fa-plus"></i> Agregar ejercicio de cardio
                            </button>
                            <br>
                            <br>
                            <div class="d-flex flex-wrap">
                                <div v-for="(cardio, index) in dia.cardio" class="ejercicio col-sm-5">
                                    <div class="d-flex flex-column float-right">
                                        <i class="far fa-times" @click="quitarCardio(cardio)"></i>
                                        <i class="far fa-database" @click="openRepoExplorer(cardio)"></i>
                                    </div>
                                    <br>
                                    <div class="video">
                                        <span class="small float-right">@{{ cardio.ejercicio.length }}/50</span>
                                        <input placeholder="Ejercicio" class="form-control"
                                                  v-model="cardio.ejercicio">
                                        <form-error :name="'cardio.'+index+'.ejercicio'" :errors="errors"></form-error>
                                        <form-error :name="'cardio.'+index+'.video'" :errors="errors"></form-error>
                                    </div>
                                </div>
                            </div>
                            <div v-if="dia.cardio.length==0">
                                [Todavía no hay ejercicios cardio registrados]
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade card" id="copia" role="tabpanel">
                        <div class="card-header">Copa de ejercicios</div>
                        <div class="card-body">
                            <label>Si deseas copiar la configuración de los ejercicios de algún otro día a este,
                                puedes seleccionar el día a copiar aquí <i class="fa fa-arrow-down"></i></label>
                            <div class="d-flex">
                                <select class="selectpicker" data-title="Copiar ejercicios de otro día"
                                        data-live-search="true"
                                        v-model="diaSeleccionado">
                                    <option v-for="(configuracion , index) in configuraciones" :value="index">
                                        @{{ configuracion }}
                                    </option>
                                </select>
                                <button class="btn btn-sm btn-default" @click="copiarConfiguracion"
                                        v-if="diaSeleccionado!=''">
                                    <i v-if="loading" class="far fa-spinner fa-spin"></i>
                                    <i v-else class="far fa-copy"></i> Copiar ejercicios a este día
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ml-1">
                    <button class="btn btn-sm btn-success" @click="guardar" :disabled="loading">
                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                        <i v-else class="far fa-save"></i>
                        Guardar
                    </button>
                    <br>
                    <ul class="text-danger small">
                        <li v-for="error in errors">@{{ error[0] }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </template>

    <template id="lugar-template">
        <div>
            <div class="d-flex justify-content-between">
                <span>@{{ title }}</span>
                <button class="btn btn-sm btn-default float-right" @click="agregarSerie(1)"
                        style="margin: 5px">
                    <i class="fa fa-plus"></i> Agregar serie
                </button>
            </div>
            <hr>
            <div v-for="(serie, iserie) in lugar">
                <div class="d-flex justify-content-between serie">
                    <div>
                        <span class="small float-right">@{{ serie.nombre.length }}/50</span>
                        <input class="form-control" v-model="serie.nombre">
                        <form-error :name="modo+'.'+iserie+'.nombre'" :errors="errors"></form-error>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-default" @click="agregarEjercicio(serie)">
                            <i class="fa fa-plus"></i> Agregar ejercicio
                        </button>
                        <button class="btn btn-sm btn-default" @click="agregarSubserie(serie)">
                            <i class="fa fa-plus"></i> Agregar subserie
                        </button>
                        <button class="btn btn-sm btn-default" @click="quitarSubserie(serie)">
                            <i class="fa fa-trash"></i> Quitar subserie
                        </button>
                        <button class="btn btn-sm btn-default" @click="quitarSerie(serie)">
                            <i class="fa fa-times"></i> Quitar serie
                        </button>
                    </div>
                </div>
                <div class="d-flex flex-wrap">
                    <div v-for="(ejercicio, iejercicio) in serie.ejercicios" class="ejercicio col-sm-5">
                        <div class="video d-flex">
                            <div v-if="ejercicio.video==''" class="vid">[Selecciona un video]</div>
                            <video poster="{{asset('img/poster.png')}}" v-else class="embed-responsive-item" preload="none" controls="auto"
                                   :src="'{{url('configuracion/ejercicio/')}}/'+ejercicio.video">
                                <source :src="'{{url('configuracion/ejercicio/')}}/'+ejercicio.video"
                                        type="video/mp4">
                            </video>
                            <div class="d-flex flex-column float-right">
                                <i class="far fa-times" @click="quitarEjercicio(serie, ejercicio)"></i>
                                <i class="far fa-database" @click="openRepoExplorer(ejercicio)"></i>
                            </div>
                        </div>
                        <div class="video">
                            <span class="small float-right">@{{ ejercicio.ejercicio.length }} / 50</span>
                            <input placeholder="Ejercicio" class="form-control"
                                   v-model="ejercicio.ejercicio"/>
                            <hr>
                            <div class="d-flex flex-wrap">
                                <div v-for="(subserie, isubserie) in ejercicio.subseries" class="repeticion">
                                    <span class="small float-right">( @{{ subserie.repeticiones.length }} / 50 )</span>
                                    <input class="form-control"
                                           v-model="subserie.repeticiones"/>
                                    <form-error :name="modo+'.'+iserie+'.ejercicios.'+iejercicio+'.subseries.'+isubserie+'.repeticiones'"
                                                :errors="errors"></form-error>
                                </div>
                            </div>
                            <form-error :name="modo+'.'+iserie+'.ejercicios.'+iejercicio+'.video'"
                                        :errors="errors"></form-error>
                            <form-error :name="modo+'.'+iserie+'.ejercicios.'+iejercicio+'.ejercicio'"
                                        :errors="errors"></form-error>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div v-if="lugar.length==0" class="ml-4">
                [Todavía no hay ejercicios registrados]
            </div>
        </div>
    </template>

    <template id="repo-explorer-template">
        <div id="repo" v-show="showing">
            <div class="static-sticky">
                <i @click="close" class="fa fa-times close"></i>
                <br>
                <div class="col-sm-12 ">
                    <div class="input-group">
                        <input type="text" class="form-control" @keyUp="buscar" v-model="nombre"
                               placeholder="Buscar archivos...">
                        <div style="margin: 10px 10px;"><i class="fa fa-search"></i></div>
                    </div>
                </div>
            </div>
            <table class="table">
                <tr v-for="archivo in archivos">
                    <td>
                        <button class="btn btn-sm btn-default" @click="agregarArchivo(archivo)">
                            <i class="fa fa-chevron-left"></i>
                        </button>
                        @{{ archivo}}
                    </td>
                </tr>
            </table>
        </div>
    </template>

@endsection
@section('scripts')
    <script>
        const eventHub = new Vue();

        Vue.mixin({
            data: function () {
                return {
                    eventHub: eventHub
                }
            }
        });

        Vue.component('programa', {
            template: '#programa-template',
            props: ['p_dia', 'usuario', 'configuraciones', 'genero', 'objetivo'],
            data: function () {
                return {
                    dia: {},
                    errors: [],
                    loading: false,
                    diaSeleccionado: ''
                }
            },
            methods: {
                agregarCardio: function () {
                    this.dia.cardio.push({
                        id: null,
                        ejercicio: '',
                        video: '',
                        tipo: '{{\App\Code\TipoEjercicio::AEROBICO}}',
                        orden: this.dia.cardio.length,
                        genero: this.genero,
                        objetivo: this.objetivo
                    });
                    this.errors = {};
                },
                quitarCardio: function (ejercicio) {
                    let index = this.dia.cardio.indexOf(ejercicio);
                    this.dia.cardio.splice(index, 1);
                    this.errors = {};
                },
                guardar: function () {
                    let vm = this;
                    this.loading = true;
                    this.errors = {};
                    axios.post('{{url('configuracion/dia')}}', this.dia).then(function (response) {
                        if (response.data.status == 'ok') {
                            window.location.href = response.data.redirect;
                        }
                        vm.loading = false;
                    }).catch(function (error) {
                        vm.loading = false;
                        if(error.response.status==500){
                            vm.errors = {general:["Error en el envío de datos del servidor"]};
                        }else{
                            vm.errors = error.response.data.errors;
                        }
                    });
                },
                copiarConfiguracion: function () {
                    this.errors = {};
                    let respuesta = confirm("La configuración permanecerá hasta que presiones \"Guardar\"\n" +
                        "Los cambios se perderán en el día actual.\n ¿Estás de acuerdo?")
                    if (respuesta) {
                        let vm = this;
                        vm.loading = true;
                        vm.copia = false;
                        let conf = this.diaSeleccionado.split("-");
                        axios.get("{{url('configuracion/getDia/')}}/" + conf[0] + "/" + conf[1] + "/" + conf[2]).then(function (response) {
                            vm.dia.gym = response.data.gym;
                            vm.dia.casa = response.data.casa;
                            vm.dia.cardio = response.data.cardio;
                            vm.dia.nota = response.data.notas[0].descripcion;
                            vm.copia = true;
                            _.each(vm.dia.gym, function (serie) {
                                serie.id = null;
                                _.each(serie.ejercicios, function (ejercicio) {
                                    ejercicio.id = null;
                                    ejercicio.genero = vm.genero;
                                    ejercicio.objetivo = vm.objetivo;
                                })
                            });
                            _.each(vm.dia.casa, function (serie) {
                                serie.id = null;
                                _.each(serie.ejercicios, function (ejercicio) {
                                    ejercicio.id = null;
                                    ejercicio.genero = vm.genero;
                                    ejercicio.objetivo = vm.objetivo;
                                })
                            });
                            _.each(vm.dia.cardio, function (ejercicio) {
                                ejercicio.id = null;
                                ejercicio.genero = vm.genero;
                                ejercicio.objetivo = vm.objetivo;
                            });
                            vm.loading = false;
                        }).catch(function (error) {
                            vm.dia.nota = '';
                            vm.errors.copia = ['La configuración de este día esta vacía'];
                            vm.loading = false;
                            vm.copia = true;
                        });
                    }
                },
                openRepoExplorer: function (ejercicio) {
                    this.eventHub.$emit('repo-open', ejercicio);
                },
            },
            created: function () {
                this.dia = this.p_dia;
                this.dia.nota = this.p_dia.nota.descripcion;
                this.dia.genero = this.genero;
                this.dia.objetivo = this.objetivo;
            },
        });

        Vue.component('repo-explorer', {
            template: '#repo-explorer-template',
            data: function () {
                return {
                    showing: false,
                    loading: false,
                    archivos: [],
                    ejercicio: {},
                    nombre: ''
                };
            },
            mounted: function () {
                var vm = this;
                this.eventHub.$on('repo-open', function (ejercicio) {
                    vm.ejercicio = ejercicio;
                    vm.open();
                });
                this.eventHub.$on('clean', function () {
                    vm.errors={};
                });
            },
            methods: {
                buscar: function () {
                    let vm = this;
                    this.loading = true;

                    axios.post('{{url('configuracion/ejercicios')}}', {nombre: this.nombre}).then(function (response) {
                        vm.archivos = response.data;
                    }).catch(function (error) {
                        vm.loading = false;
                        vm.errors = error.response.data.errors;
                    })
                },
                close: function () {
                    this.showing = false;
                },
                open: function () {
                    this.buscar();
                    this.showing = true;
                },
                agregarArchivo: function (video) {
                    this.ejercicio.video = video;
                    this.close();
                },
            }
        });

        Vue.component('lugar', {
            template: '#lugar-template',
            props: ['title', 'serie', 'lugar', 'errors', 'modo', 'genero', 'objetivo'],
            methods: {
                buildEjercicio: function () {
                    return {
                        id: null,
                        ejercicio: '',
                        video: '',
                        tipo: '{{\App\Code\TipoEjercicio::ANAEROBICO}}',
                        subseries: [],
                        genero: this.genero,
                        objetivo: this.objetivo,
                        lugar: this.modo=='gym'?'{{\App\Code\LugarEjercicio::GYM}}':'{{\App\Code\LugarEjercicio::CASA}}'
                    };
                },
                agregarSerie: function () {
                    this.eventHub.$emit('clean');
                    let serie = {
                        id: null,
                        nombre: '',
                        orden: this.lugar.length,
                        genero: this.genero,
                        objetivo: this.objetivo,
                        ejercicios: []
                    };
                    this.agregarEjercicio(serie);
                    this.lugar.push(serie);
                },
                quitarSerie: function (serie) {
                    this.eventHub.$emit('clean');
                    let index = this.lugar.indexOf(serie);
                    this.lugar.splice(index, 1);
                },
                agregarEjercicio: function (serie) {
                    this.eventHub.$emit('clean');
                    let ejercicio = this.buildEjercicio();
                    ejercicio.orden = serie.ejercicios.length;
                    if (serie.ejercicios.length > 0) {
                        _.each(serie.ejercicios[0].subseries, function (subserie) {
                            ejercicio.subseries.push({repeticiones: 1})
                        });
                    } else {
                        ejercicio.subseries.push({repeticiones: 1})
                    }
                    serie.ejercicios.push(ejercicio)
                },
                quitarEjercicio: function (serie, ejercicio) {
                    this.eventHub.$emit('clean');
                    let index = serie.ejercicios.indexOf(ejercicio);
                    serie.ejercicios.splice(index, 1);
                },
                agregarSubserie: function (serie) {
                    this.eventHub.$emit('clean');
                    _.each(serie.ejercicios, function (ejercicio) {
                        ejercicio.subseries.push({repeticiones: 1});
                    });
                },
                quitarSubserie: function (serie) {
                    this.eventHub.$emit('clean');
                    _.each(serie.ejercicios, function (ejercicio) {
                        if (ejercicio.subseries.length > 1) {
                            ejercicio.subseries.splice(ejercicio.subseries.length - 1, 1);
                        }
                    });
                },
                openRepoExplorer: function (ejercicio) {
                    this.eventHub.$emit('repo-open', ejercicio);
                },
            },
            created: function () {
                localStorage.setItem('genero', this.genero);
                localStorage.setItem('objetivo', this.objetivo);
            }
        });

        var vue = new Vue({
            el: '#vue'
        });

    </script>

@endsection
