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
        .stepwizard-step p {
            margin-top: 0px;
            color:#666;
        }
        .stepwizard-row {
            display: table-row;
        }
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }
        .stepwizard-step button[disabled] {
            /*opacity: 1 !important;
            filter: alpha(opacity=100) !important;*/
        }
        .stepwizard .btn.disabled, .stepwizard .btn[disabled], .stepwizard fieldset[disabled] .btn {
            opacity:1 !important;
            color:#bbb;
        }
        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content:" ";
            width: 100%;
            height: 1px;
            background-color: #ccc;
            z-index: 0;
        }
        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }
    </style>
    <script>
        $(document).ready(function () {

            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');
                allPrevBtn = $('.prevBtn');

            allWells.hide();

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn-success').addClass('btn-default');
                    $item.addClass('btn-success');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            });

            allPrevBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }

                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-success').trigger('click');
        });
    </script>
    @endsection
@section('content')
    <div id="vue">
        <div class="container">
            <cuenta :p_user="{{$user}}"></cuenta>
        </div>
    </div>
    <template id="cuenta-template">
        <div class="card">
            <div class="card-header"><i class="far fa-user"></i> Mi cuenta</div>
            <div class="card-body">

                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-xs-3">
                            <a href="#step-1" type="button" class="btn btn-success btn-circle">1</a>
                            <p><small>Etapa 1</small></p>
                        </div>
                        <div class="stepwizard-step col-xs-3">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                            <p><small>Etapa 2</small></p>
                        </div>
                        <div class="stepwizard-step col-xs-3">
                            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
                            <p><small>Etapa 3</small></p>
                        </div>
                    </div>
                </div>



                <form role="form">
                    <div class="panel panel-primary setup-content" id="step-1">
                        <div class="panel-heading">
                            <h3 class="panel-title">Etapa 1</h3>
                        </div>
                        <div class="panel-body">
                            <div class="d-flex flex-wrap">
                                <div class="col-sm-4 col-12 text-center" style="border: 2px dashed grey; padding: 5px;" @drop.prevent="cargarFoto($event)" @dragover.prevent>
                                    <div>
                                        <i v-if="loadingFoto" class="fa fa-spinner fa-spin"></i>
                                        <img v-else id="fotografia" :src="fotografia"
                                             width="200">
                                    </div>
                                    <div>
                                        <label for="foto" :class="loading?'disabled':''" class="custom-file-upload">
                                            <i class="fa fa-cloud-upload"></i> Sube tu foto
                                        </label>
                                        <br>
                                        <span>O arrastra el archivo desde tu computadora</span>
                                        <input id="foto" type="file" @change="cargarFoto($event)" :disabled="loading">
                                        <form-error name="imagen" :errors="errors"></form-error>
                                    </div>
                                </div>
                                <div class="col-sm-8 col-12">
                                    <div>
                                        <label>Correo</label>
                                        <label class="form-control disabled">@{{ user.email }}</label>
                                        <br>
                                    </div>
                                    <div>
                                        <label class="required">Contraseña</label>
                                        <input type="password" class="form-control" v-model="user.pass_confirmation">
                                        <form-error name="pass_confirmation" :errors="errors"></form-error>
                                    </div>
                                    <div>
                                        <label class="required">Confirmar contraseña</label>
                                        <input type="password" class="form-control" v-model="user.pass">
                                        <form-error name="pass" :errors="errors"></form-error>
                                    </div>
                                    <div>
                                        <!--label>Tarjeta para depositar comisiones <span class="small">(por favor verifica que sea correcta)</span></label>
                                        <input :class="'form-control '+(user.tarjeta==null?'required':'not')" v-model="user.tarjeta" maxlength="16">
                                        <span class="float-right small">@{{ user.tarjeta.length }}/16</span>
                                        <form-error name="tarjeta" :errors="errors"></form-error-->
                                    </div>
                                    <div>
                                        <br>
                                        <button class="btn btn-success" :disabled="loading" @click="save()">
                                            <i v-if="loading" class="far fa-spinner fa-spin"></i>
                                            <i v-else class="far fa-save"></i>
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary prevBtn pull-right" type="button">Anterior</button>
                            <button class="btn btn-primary nextBtn pull-right" type="button">Siguiente</button>
                        </div>
                    </div>

                    <div class="panel panel-primary setup-content" id="step-2">
                        <div class="panel-heading">
                            <h3 class="panel-title">Etapa 2</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row col-md-12">

                                <div class="col-sm-3">
                                    <label>Estado</label>
                                    <select class="form-control" v-model="filtros.estado" @keyup.enter="buscar" @change="getCiudades()">
                                        <option></option>
                                        <option v-for="p in this.estados[0]">@{{ p.estado }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Ciudad</label>
                                    <select class="form-control" v-model="filtros.ciudad" @keyup.enter="buscar" @change="getCPs()">
                                        <option></option>
                                        <option v-for="p in this.ciudades[0]">@{{ p.ciudad }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Codigo Postal</label>
                                    <select class="form-control" v-model="filtros.cp" @keyup.enter="buscar" @change="getColonias()">
                                        <option></option>
                                        <option v-for="p in this.cps[0]">@{{ p.cp }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label>Colonias</label>
                                    <select class="form-control" v-model="filtros.colonia" @keyup.enter="buscar">
                                        <option></option>
                                        <option  v-for="p in this.colonias[0]">@{{ p.colonia }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label v-if="guardado">Ubicacion actualizada</label>
                                    <br>
                                    <button class="btn btn-success" @click="guardarLugar">
                                        <i class="fas fa-save"></i>&nbsp;Guardar ubicación
                                    </button>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary prevBtn pull-right" type="button">Anterior</button>
                            <button class="btn btn-primary nextBtn pull-right" type="button">Siguiente</button>
                        </div>
                    </div>

                    <div class="panel panel-primary setup-content" id="step-3">
                        <div class="panel-heading">
                            <h3 class="panel-title">Etapa 3</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row col-md-12">

                                <div class="col-sm-3">
                                    <label>Edad</label>
                                    <input type="number" min="0" class="form-control" v-model="user.edad">
                                    <input type="checkbox" id="edad_publico" v-model="user.edad_publico"> <label for="publico">Público</label>
                                    <form-error name="edad" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <label>GYM</label>
                                    <input type="text" class="form-control" v-model="user.gym">
                                    <input type="checkbox" id="edad_publico" v-model="user.gym_publico"> <label for="publico">Público</label>
                                    <form-error name="gym" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <label>Idiomas que hablas</label>
                                    <select class="form-control" v-model="user.idiomas" multiple>
                                        <option value="Español">Español</option>
                                        <option value="Ingles">Ingles</option>
                                        <option value="Frances">Frances</option>
                                        <option value="Chino">Chino</option>
                                        <option value="Japones">Japones</option>
                                        <option value="Aleman">Aleman</option>
                                        <option value="Portugues">Portugues</option>
                                    </select>
                                    <input type="checkbox" id="edad_publico" v-model="user.idiomas_publico"> <label for="publico">Público</label>
                                    <form-error name="idiomas" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <label>Estudios</label>
                                    <input type="text" class="form-control" v-model="user.estudios">
                                    <input type="checkbox" id="edad_publico" v-model="user.estudios_publico"> <label for="publico">Público</label>
                                    <form-error name="estudios" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <label>Empleo</label>
                                    <input class="form-control" v-model="user.empleo">
                                    <input type="checkbox" id="edad_publico" v-model="user.empleo_publico"> <label for="publico">Público</label>
                                    <form-error name="empleo" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <label>Elige 5 intereses</label>
                                    <select class="form-control" v-model="user.intereses" multiple>
                                        <option value="Deportes" >Deportes</option>
                                        <option value="Cine" >Cine</option>
                                        <option value="Espiritualidad" >Espiritualidad</option>
                                        <option value="Bailar" >Bailar</option>
                                        <option value="Viajar" >Viajar</option>
                                        <option value="Música" >Música</option>
                                        <option value="Leer" >Leer</option>
                                        <option value="Gastronomía" >Gastronomía</option>
                                        <option value="Animales" >Animales</option>
                                        <option value="Idiomas" >Idiomas</option>
                                        <option value="Astrología" >Astrología</option>
                                        <option value="Cantar" >Cantar</option>
                                        <option value="Futbol" >Futbol</option>
                                        <option value="Yoga" >Yoga</option>
                                        <option value="Arte" >Arte</option>
                                        <option value="Politica" >Politica</option>
                                        <option value="Negocios" >Negocios</option>
                                    </select>
                                    <input type="checkbox" id="edad_publico" v-model="user.intereses_publico"> <label for="publico">Público</label>
                                    <form-error name="intereses" :errors="errors"></form-error>
                                </div>

                                <div class="col-sm-3">
                                    <br>
                                    <button class="btn btn-success" @click="guardarInfoGeneral">
                                        <i class="fas fa-save"></i>&nbsp;Guardar datos personales
                                    </button>
                                </div>
                            </div>
                            <br>
                            <button class="btn btn-primary prevBtn pull-right" type="button">Anterior</button>
                        </div>
                    </div>


                </form>




                <!--br>
                <div class="row col-md-12">
                    <div class="col-sm-12">
                        <b>Edad:</b>
                        @{{ user.edad }}
                    </div>
                    <div class="col-sm-12">
                        <strong>Estudios:</strong>
                        @{{ user.estudios }}
                    </div>
                    <div class="col-sm-12">
                        <strong>Empleo:</strong>
                        @{{ user.empleo }}
                    </div>
                    <div class="col-sm-12">
                        <strong>Idiomas:</strong>
                        @{{ user.idiomas }}
                    </div>
                    <div class="col-sm-12">
                        <strong>GYM:</strong>
                        @{{ user.gym }}
                    </div>
                    <div class="col-sm-12">
                        <strong>Intereses:</strong>
                        @{{ user.intereses }}
                    </div>
                    </div>
                </div-->
                <br>


                <br>



            </div>
    </template>
@endsection
@section('scripts')
    <script>
        Vue.component('cuenta', {
            template: '#cuenta-template',
            props: ['p_user'],
            data: function () {
                return {
                    user: {},
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
