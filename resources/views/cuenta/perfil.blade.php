@extends('layouts.app')
@section('header')
    <style>
        input[type="file"] {
            display: none;
        }

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }

        label.disabled{
            background-color: #f3f3f3;
        }

        input.required{
            border-color: #9c1f2d;
        }
        .font-weight-bold {
            font-size: 18px;
        }
        small{
            font-size: 1.5em !important;
        }

        .biggest {
            font-size: 3em;
            line-height: 1 !important;
            font-family: unitext_cursive !important;
            font-weight:bold;
            font-size: 1.5em;
            text-transform: uppercase !important;
        }
        .acton{
            font-size: 15px !important;
        }
    </style>
    @endsection
@section('content')
    <div id="vue">
        <div class="container">
            <cuenta :p_user="{{$user}}" :p_amistades="{{$amistades}}"></cuenta>
        </div>
    </div>
    <template id="cuenta-template">
        <div class="row">
        <div class="card col-md-3 col-sm-12">
            <div class="card-header"><i class="far fa-user"></i> Mi cuenta</div>
            <div class="card-body">
                <div class="d-flex flex-wrap">
                    <div class="col-sm-12 col-12 text-center" style="">
                        <div>
                            <img id="fotografia" :src="'/cuenta/getFotografia/'+user.id+'/232'"
                                 width="200" />
                            <br>
                            <br>
                            <strong class="">@{{ user.name }} @{{ user.last_name }}</strong><br>
                            <small class="">@{{ user.email }}</small>
                            <br>
                            <i class="fas fa-user-friends"></i> Amigos ( @{{ amistades }} )
                            <br>
                            <br>
                            <small class="acton">Codigo personal: @{{ user.referencia }}</small>
                            <br>
                            <small class="acton">Dinero Acton: $@{{ user.saldo }}</small>
                            <br>
                            <br>
                            <a :href="'{{ url('/usuarios/imagenes') }}/' + user.id" class="btn btn-primary btn-lg" style="color: white;">Ver reto</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-md-8 col-sm-12">
            <div class="card-header"><i class="far fa-user"></i> Mi información</div>
                <div class="card-body">
                    <div class="col-sm-12 col-12">
                        <div id="verdadero">
                            <br>
                            <div class="row col-md-12">
                                <div v-if="user.edad_publico" class="col-sm-12">
                                    <span class="text-uppercase font-weight-bold biggest">Edad:</span>
                                    <span  class="text-uppercase bigger thin">@{{ user.edad }}</span>
                                </div>
                                <div v-if="user.estudios_publico" class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Estudios:</strong>
                                    <small>@{{ user.estudios }}</small>
                                </div>
                                <div v-if="user.empleo_publico" class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Empleo:</strong>
                                    <small>@{{ user.empleo }}</small>
                                </div>
                                <div v-if="user.idiomas_publico" class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Idiomas:</strong>
                                    <small>@{{ user.idiomas }}</small>
                                </div>
                                <div v-if="user.gym_publico" class="col-sm-12">
                                    <strong class="font-weight-bold biggest">GYM:</strong>
                                    <small>@{{ user.gym }}</small>
                                </div>
                                <div v-if="user.intereses_publico" class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Intereses:</strong>
                                    <small>@{{ user.intereses }}</small>
                                </div>


                                <div class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Estado:</strong>
                                    <small>@{{ user.estado }}</small>
                                </div>

                                <div class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Ciudad:</strong>
                                    <small>@{{ user.ciudad }}</small>
                                </div>

                                <div class="col-sm-12">
                                    <strong class="font-weight-bold biggest">CP:</strong>
                                    <small>@{{ user.cp }}</small>
                                </div>

                                <div class="col-sm-12">
                                    <strong class="font-weight-bold biggest">Colonia:</strong>
                                    <small>@{{ user.colonia }}</small>
                                </div>
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
        Vue.component('cuenta', {
            template: '#cuenta-template',
            props: ['p_user', 'p_amistades'],
            data: function () {
                return {
                    user: {},
                    amistades: 0,
                    errors: [],
                    loading: false,
                    loadingFoto: false,
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],
                    guardado: false,
                    fotografia: '{{url('cuenta/getFotografia/'.\Illuminate\Support\Facades\Auth::user()->id).'/'.rand(0,10)}}',
                    filtros: {
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: '',
                        estado: '0',
                        ciudad: '0',
                        cp: '0',
                        estado: '0',
                        colonia: '0',
                        ingresadosReto: '',
                        edad: 0,
                        gym: '',
                        intereses: '',
                        idiomas: '',
                        empleo: '',
                        estudios: ''
                    }
                }
            },
            methods: {
                cargarFoto: function (event) {
                    let imagen = null;
                    if (event.dataTransfer == undefined){
                        imagen = event.target.files[0];
                    }else{
                        imagen = event.dataTransfer.files[0];
                    }
                    let vm = this;
                    let fm = new FormData();
                    vm.loadingFoto = true;
                    vm.errors = [];
                    fm.append('id', this.user.id);
                    fm.append('imagen', imagen);
                    axios.post('{{url('cuenta/subirFoto')}}', fm).then(function (response) {
                        vm.loadingFoto = false;
                        if (response.data.status == 'ok'){
                            Vue.nextTick(function () {
                                vm.fotografia = response.data.imagen;
                            });
                        }
                    }).catch(function (error) {
                        vm.loadingFoto = false;
                        console.log(error.response.data.errors)
                        vm.errors = error.response.data.errors;
                    });
                },
                guardarLugar: function(){
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                        }
                        ).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                        this.guardado = true;
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                guardarInfoGeneral: function(){
                    axios.post('{{url('/usuarios/guardaInfoGeneral')}}',
                        {
                            edad: this.user.edad,
                            gym: this.user.gym,
                            intereses: this.user.intereses,
                            empleo: this.user.empleo,
                            estudios: this.user.estudios,
                            idiomas: this.user.idiomas,
                            edad_publico: this.user.edad_publico,
                            estudios_publico: this.user.estudios_publico,
                            gym_publico: this.user.gym_publico,
                            intereses_publico: this.user.intereses_publico,
                            empleo_publico: this.user.empleo_publico,
                            idiomas_publico: this.user.idiomas_publico,
                        }
                        ).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                        this.guardado = true;
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getEstados: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getEstados')}}').then((response) => {
                        this.estados=[];
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.estados.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCiudades: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCiudades')}}', {estado:this.filtros.estado}).then((response) => {
                        this.ciudades=[];
                        this.cps=[];
                        this.colonias=[];
                        this.ciudades.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getCPs: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getCP')}}', {ciudad:this.filtros.ciudad}).then((response) => {
                        this.cps=[];
                        this.colonias=[];
                        this.cps.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getColonias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getColonias')}}', {cp:this.filtros.cp}).then((response) => {
                        this.colonias=[];
                        this.colonias.push(response.data);
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                save: function () {
                    let vm = this;
                    vm.loading = true;
                    vm.errors = [];
                    axios.post('{{url('cuenta')}}', this.user).then(function (response) {
                        vm.loading = false;
                        if (response.data.status == 'ok'){
                            window.location.href = response.data.redirect;
                        }
                    }).catch(function (error) {
                        vm.errors = error.response.data.errors;
                        vm.loading = false;
                    });
                }
            },
            mounted: function () {
                this.getEstados();
            },
            created: function () {
                this.user = this.p_user;
                this.amistades = this.p_amistades;
                this.user.pass_confirmation = this.user.pass;
                this.filtros.estado = this.user.estado;
                setTimeout(() => this.getCiudades() = false, 500);
                setTimeout(() => this.filtros.ciudad = this.user.ciudad, 700);
                setTimeout(() => this.getCPs() = false, 800);
                setTimeout(() => this.filtros.cp = this.user.cp, 1000);
                setTimeout(() => this.getColonias() = false, 1100);
                setTimeout(() => this.filtros.colonia = this.user.colonia, 1300);

            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
