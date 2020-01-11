@extends('layouts.app')
@section('header')
    <style>
        .ejercicio {
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 5px;
            padding: 5px 5px 10px 5px;
            margin: 5px;
        }
    </style>
@endsection
@section('content')

<div id="vue">
    <div class="container">
        <suplementos :p_kits="{{$kits}}"></suplementos>
    </div>
</div>

<template id="temp-suplemento" >
    <div class="card">
        <div class="card-header">
            <i class="far fa-prescription-bottle"></i> Suplementos
        </div>
        <div class="card-body">
            <div style="display: flex; justify-content: space-between">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#HB" role="tab" @click="showTab='HB'"
                           aria-selected="false" >Hombre bajar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#HS" role="tab" @click="showTab='HS'"
                           aria-selected="true" >Hombre subir</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#MB" role="tab" @click="showTab='MB'"
                           aria-selected="false" >Mujer bajar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#MS" role="tab" @click="showTab='MS'"
                           aria-selected="false" >Mujer subir</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div v-for="(kits, tipo) in suplementos" :class="'tab-pane fade '+activarTab(tipo) " :id="tipo.substring(0,1)" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <span>Etapa @{{ parseInt(tipo[2])+1 }}</span> {{--Etapa HB0,HB1..,--}}
                            <button class="btn btn-sm btn-default float-right" @click="agregarSuplemento(tipo)">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap" v-if="kits.length!=0">
                                <div class="ejercicio" v-for="(suplemento, index) in kits" >
                                    <div class="float-right" @click="quitarSuplemento(suplemento, tipo)">
                                        <i class="far fa-times" ></i>
                                    </div>
                                    <label class="required">Nombre del suplemento</label>
                                    <input class="form-control" v-model="suplemento.suplemento" />
                                    <form-error :name="'suplementos.'+tipo+'.'+index+'.suplemento'" :errors="errors"></form-error>
                                    <label class="required">Porción</label>
                                    <input class="form-control" v-model="suplemento.porcion" />
                                    <form-error :name="'suplementos.'+tipo+'.'+index+'.porcion'" :errors="errors"></form-error>
                                </div>
                            </div>
                            <div v-else>
                                [No hay suplementos]
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div>
                    <button class="btn btn-sm btn-success" @click="save()" :disabled="guardando">
                        <i v-if="guardando" class="far fa-spinner fa-spin"></i>
                        <i v-else class="far fa-save"></i>
                        Guardar
                        <i class="fas fa-check-circle" v-if="guardado" ></i>
                    </button>
                    <div>
                        <ul style="padding: 5px">
                            <li v-for="(error, index) in errors">@{{ mostrarError(error, index) }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


@endsection
@section('scripts')

<script>

    Vue.component('suplementos',{
        template: '#temp-suplemento',
        props:['p_kits'],
        data: function () {
            return {
                errors: {},
                showTab: 'HB',
                suplementos: { // para mostrar los archivos en las tabs de inicio
                    HB0:[],
                    HB1:[],
                    HS0:[],
                    HS1:[],
                    MB0:[],
                    MB1:[],
                    MS0:[],
                    MS1:[]
                },
                guardado: false,
                elimSuplementos: [],
                guardando: false
            }
        },
        methods: {
            activarTab: function (tab) {
                tab = tab.substring(0,2);
                return tab==this.showTab?'active show':'';
            },
            agregarSuplemento: function (genObj) {
                this.guardado = false;
                this.suplementos[genObj] = [...this.suplementos[genObj], {id:'', suplemento:'', porcion:'', kit:genObj}];
            },
            quitarSuplemento:function (suplemento, objGen) {
                this.guardado = false;
                if (suplemento.id != ''){
                    this.elimSuplementos = [...this.elimSuplementos, {id:suplemento.id, kit:suplemento.kit_id, descripcion:objGen}];
                }
                const index = this.suplementos[objGen].indexOf(suplemento);
                this.suplementos[objGen].splice(index, 1);
            },
            save: function () {
                let vm = this;
                vm.guardando = true;
                vm.errors = [];
                axios.post('{{url('/suplementos/save')}}', {suplementos: vm.suplementos, eliminados:vm.elimSuplementos})
                    .then(function (response){
                        if(response.data.status = 'ok') {
                            vm.asignarKit(response.data.kitsDB);
                            vm.guardado = true;
                            vm.guardando = false;
                        }
                    })
                    .catch(function (errors){
                        vm.guardando = false;
                        vm.errors = errors.response.data.errors;
                    });
            },
            asignarKit: function (kits) { //asignar suplementos de cada kit correspondiente
                let vm = this;
                _.forEach(kits, function (kit, index) {
                    vm.suplementos[index] = kit;
                });
            },
            mostrarError: function (error, index) {
                let err = index.split('.');
                let pestana = err[1].substr(0,2);
                let num = parseInt(err[2])+1;
                switch (pestana){
                    case 'HB':
                        return error+" en suplemento "+num+" dentro de hombre bajar";
                    case 'HS':
                        return error+" en suplemento "+num+" dentro de hombre subir";
                    case 'MB':
                        return error+" en suplemento "+num+" dentro de mujer bajar";
                    case 'MS':
                        return error+" en suplemento "+num+" dentro de mujer subir";
                }
            }
        },
        mounted:function(){
            let vm = this;
            vm.asignarKit(vm.p_kits);
        }
    });

    var vue = new Vue({
        el:'#vue'
    });

</script>

@endsection