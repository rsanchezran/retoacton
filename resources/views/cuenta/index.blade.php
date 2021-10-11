@extends('layouts.app_datos')
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
        .Mujer { /* Microsoft Edge */
            color: #B400B9 !important;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .Hombre { /* Microsoft Edge */
            color: #0080DD !important;
            font-size: 25px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn-circle {
            width: auto;
            height: auto;
            text-align: center;
            padding: 6px 0;
            font-size: 15px;
            line-height: 1.428571429;
            border-radius: 0px;
        }
        .btn-success, .btn-default{
            background: transparent;
            border: 0px;
            color: #0080DD !important;
        }
        .stepwizard-row:before {
            top: 14px;
            bottom: 0;
            position: absolute;
            content: " ";
            width: 100%;
            height: 1px;
            background-color: transparent;
            z-index: 0;
        }
        .btn-success:hover {
            color: #fff;
            background-color: transparent;
            border-color: #0080DD;
        }
        .btn-success:not(:disabled):not(.disabled):active, .btn-success:not(:disabled):not(.disabled).active, .show > .btn-success.dropdown-toggle {
            color: #0080DD !important;
            border-bottom-color: #0080DD !important;
        }
        .stepwizard-step a{
            color: #c2c2c2 !important;
        }
        .stepwizard-step a:hover{
            color: #0080DD !important;
        }
        .cambiacolor{
            color: #0080DD !important;
            border-bottom: 3px solid #0080DD !important;
        }
        .multiselect__tag {
            background: gray !important;
        }
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
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
                if($(this).attr('href') == '#step-1'){
                    $("#pasouno").addClass('cambiacolor');
                    $("#pasodos").removeClass('cambiacolor');
                    $("#pasotres").removeClass('cambiacolor');
                }
                if($(this).attr('href') == '#step-2'){
                    $("#pasouno").removeClass('cambiacolor');
                    $("#pasodos").addClass('cambiacolor');
                    $("#pasotres").removeClass('cambiacolor');
                }
                if($(this).attr('href') == '#step-3'){
                    $("#pasouno").removeClass('cambiacolor');
                    $("#pasodos").removeClass('cambiacolor');
                    $("#pasotres").addClass('cambiacolor');
                }

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
            <!--div class="card-header"><i class="far fa-user"></i> Mi cuenta</div-->
            <div class="card-body">

                <div class="stepwizard">
                    <div class="stepwizard-row setup-panel">
                        <div class="stepwizard-step col-xs-3" id="pasouno" style="border-bottom:1px solid #c2c2c2;">
                            <a href="#step-1" type="button" class="btn btn-success btn-circle">ETAPA 1</a>
                            <p><small></small></p>
                        </div>
                        <div class="stepwizard-step col-xs-3" id="pasodos" style="border-bottom:1px solid #c2c2c2;">
                            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">ETAPA 2</a>
                            <p><small></small></p>
                        </div>
                        <div class="stepwizard-step col-xs-3 " id="pasotres" style="border-bottom:1px solid #c2c2c2;">
                            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">ETAPA 3</a>
                            <p><small></small></p>
                        </div>
                    </div>
                </div>
                <br>
                



                <form role="form">
                    <div class="panel panel-primary setup-content" id="step-1">
                        <div class="panel-body">
                            <div class="d-flex flex-wrap">
                                <div class="col-sm-4 col-12 text-center" style="border: 0px dashed grey; padding: 5px;" @drop.prevent="cargarFoto($event)" @dragover.prevent>
                                    <img id="fotografia" src="{{asset('images/2021/sube_foto_text.png')}}" class="w-100" style="margin-bottom: 20px;">
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
                                        <input id="foto" type="file" @change="cargarFoto($event)" :disabled="loading">
                                        <form-error name="imagen" :errors="errors"></form-error>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-12 text-center">
                                    <img id="fotografia" src="{{asset('images/2021/cambiar_codigo.png')}}" class="w-100 col-12" style="margin-bottom: 20px;">
                                </div>

                                <div class="col-sm-8 col-12">
                                    <div>
                                        <label>CÓDIGO ASIGNADO</label>
                                        <label class="form-control disabled referencia">@{{ user.referencia }}</label>
                                        <br>
                                    </div>
                                    <div>
                                        <label class="required">ELIGE TU NUEVO CÓDIGO</label>
                                        <input type="text" class="form-control" v-model="user.codigo_nuevo">
                                        <form-error name="codigo_nuevo" :errors="errors"></form-error>
                                    </div>
                                    <div v-html="mensaje"></div>
                                </div>
                                <div class="text-center col-12">
                                    <br>
                                    <br>
                                    <h3>Tú codigo QR</h3>
                                    <br>
                                    <qrcode :value="'https://retoacton.com/registro/gratis/?codigo='+user.referencia" :options="{ width: 200 }" @ready="onReady"></qrcode>
                                    <!--button class="btn btn-success" :disabled="loading" @click="save()">
                                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                                        <i v-else class="far fa-save"></i>
                                        Guardar
                                    </button-->
                                </div>
                            </div>

                                <!--div class="col-sm-8 col-12">
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
                                <!--/div>
                                <div>
                                    <br>
                                    <button class="btn btn-success" :disabled="loading" @click="save()">
                                        <i v-if="loading" class="far fa-spinner fa-spin"></i>
                                        <i v-else class="far fa-save"></i>
                                        Guardar
                                    </button>
                                </div>
                            </div-->
                            </div>
                            <br>
                            <!--button class="btn btn-primary prevBtn pull-right" type="button">Anterior</button-->
                            <button class="btn nextBtn pull-right" type="button" @click="guardarLugar"><img  src="{{asset('images/2021/ontinuar.png')}}" class="w-100" style="margin-bottom: 20px;"></button>
                        </div>
                    </div>

                    <div class="panel panel-primary setup-content" id="step-2">
                        <div class="panel-heading">
                            <h3 class="panel-title"></h3>
                        </div>
                        <div class="panel-body">
                            <div class=" col-12">
                                
                                <div :class="sexo" class="text-center">Datos personales</div>
                                <br>

                                <div class="col-sm-3">
                                    <vue-multiselect v-model="user.intereses" :options="intereses" :preselect-first="false" :multiple="true" placeholder="Intereses personales"  :preserve-search="false"></vue-multiselect>
                                    <input type="checkbox" id="intereses_publico" v-model="user.intereses_publico"> <label for="publico">Mostrar público en mi perfil</label>
                                    <form-error name="intereses" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">sexo</div>
                                    <br>
                                    <label>Soy</label>
                                    <br>
                                </div>
                                <div class="col-sm-3">
                                    <vue-multiselect v-model="user.genero" :options="genero" :preselect-first="false" :multiple="false" placeholder="Sexo"  :preserve-search="false"></vue-multiselect>
                                    <vue-multiselect v-model="user.genero_2" :options="genero_2" :preselect-first="false" :multiple="false" placeholder="Genero"  :preserve-search="false"></vue-multiselect>
                                    <form-error name="idiomas" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">Mi situación actual</div>
                                    <br>
                                </div>

                                <div class="col-sm-3">
                                    <vue-multiselect v-model="user.situacion_actual" :options="situacion" :preselect-first="false" :multiple="false" placeholder="En este momento me encuentro"  :preserve-search="false"></vue-multiselect>

                                    <input type="checkbox" id="edad_publico" v-model="user.situacion_actual_publico"> <label for="publico">Mostrar público en mi perfil</label>
                                    <form-error name="idiomas" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">Qué idiomas hablas</div>
                                    <br>
                                </div>

                                <div class="col-sm-3">
                                    <vue-multiselect v-model="user.idiomas" :options="idiomas" :preselect-first="false" :multiple="true" placeholder="Idiomas"  :preserve-search="false"></vue-multiselect>

                                    <input type="checkbox" id="edad_publico" v-model="user.idiomas_publico"> <label for="publico">Mostrar público en mi perfil</label>
                                    <form-error name="idiomas" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">Dónde entrenas</div>
                                    <br>
                                </div>

                                <div class="col-sm-3">

                                    <!--div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" v-model="user.">
                                        <label class="btn btn-outline-primary" for="btnradio1">Casa</label>


                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                                        <label class="btn btn-outline-primary" for="btnradio3">Gym</label>
                                    </div-->

                                    <input type="text" placeholder="GYM" class="form-control" v-model="user.gym">
                                    <input type="checkbox" id="edad_publico" v-model="user.gym_publico"> <label for="publico">Mostrar público en mi perfil</label>
                                    <form-error name="gym" :errors="errors"></form-error>
                                    <vue-multiselect v-model="filtros.estado_gym" :options="this.estados_gym" :preselect-first="false" :multiple="false" placeholder="Estado" @input="getCiudades()"  :preserve-search="false"></vue-multiselect>
                                    <vue-multiselect v-model="filtros.ciudad_gym" :options="this.ciudades_gym" :preselect-first="false" :multiple="false" placeholder="Ciudad" @input="getCPs()"  :preserve-search="false"></vue-multiselect>
                                    <input type="text" class="form-control" v-model="user.gym_ciudad" placeholder="Ciudad">
                                    <form-error name="gym_ciudad" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">Mi ubicación</div>
                                    <br>
                                </div>

                                <div class="col-sm-3">
                                    <vue-multiselect v-model="filtros.estado" :options="this.estados" :preselect-first="false" :multiple="false" placeholder="Estado" @input="getCiudades()"  :preserve-search="false"></vue-multiselect>
                                </div>
                                <br>
                                <div class="col-sm-3">
                                    <vue-multiselect v-model="filtros.ciudad" :options="this.ciudades" :preselect-first="false" :multiple="false" placeholder="Ciudad" @input="getCPs()"  :preserve-search="false"></vue-multiselect>
                                </div>
                                <br>
                                <div class="col-sm-3">
                                    <vue-multiselect v-model="filtros.cp" :options="this.cps" :preselect-first="false" :multiple="false" placeholder="CP" @input="getColonias()"  :preserve-search="false"></vue-multiselect>
                                </div>
                                <br>
                                <div class="col-sm-3">
                                    <vue-multiselect v-model="filtros.colonia" :options="this.colonias" :preselect-first="false" :multiple="false" placeholder="Colonia" @input="getColonias()"  :preserve-search="false"></vue-multiselect>
                                </div>
                                <br>

                                <div class="col-sm-3">
                                    <input type="text" class="form-control" v-model="user.calle" placeholder="Calle">
                                    <form-error name="calle" :errors="errors"></form-error>
                                </div>
                                <br>

                                <div class="col-sm-3">
                                    <input type="text" class="form-control" v-model="user.numero" placeholder="Número interior y exterior">
                                    <form-error name="numero" :errors="errors"></form-error>
                                </div>

                                <div class="col-12 text-center">
                                    <br>
                                    <div :class="sexo" class="text-center">edad</div>
                                    <br>
                                </div>

                                <div class="col-sm-3">
                                    <input type="number" min="0" class="form-control" v-model="user.edad" placeholder="Edad">
                                    <input type="checkbox" id="edad_publico" v-model="user.edad_publico"> <label for="publico">Mostrar público en mi perfil</label>
                                    <form-error name="edad" :errors="errors"></form-error>
                                </div>

                            </div>
                            <br>
                            <div class="col-12 text-center">
                                <button class="prevBtn pull-right" @click="guardarLugar" type="button" style="background: transparent !important;border: 0px !important;}"><img src="{{asset('images/2021/anterior.png')}}" class="w-25"></button>
                                <button class="nextBtn pull-right" @click="guardarLugar" type="button" style="background: transparent !important;border: 0px !important;}"><img src="{{asset('images/2021/siguiente.png')}}" class="w-25"> </button>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary setup-content" id="step-3">
                        <div class="panel-heading">
                            <h3 class="panel-title"></h3>
                        </div>
                        <div class="panel-body">
                            <div class="col-12">
                                <div class="col-12 text-center">
                                        <img src="{{asset('images/2021/datos_bancarios.png')}}" class="w-100">
                                </div>
                                <BR>

                                <div class="col-sm-3 text-center">
                                    <label class="text-center">NÚMERO DE TARJETA</label>
                                    <input type="text" class="form-control" v-model="user.numero_tarjeta" placeholder="Numero de tarjeta">
                                    <form-error name="numero_tarjeta" :errors="errors"></form-error>
                                </div>
                                <BR>

                                <div class="col-sm-3 text-center">
                                    <label class="text-center">BANCO</label>
                                    <input type="text" class="form-control" v-model="user.banco">
                                    <form-error name="banco" :errors="errors"></form-error>
                                </div>
                                <div class="col-12 text-center">
                                    <img src="{{asset('images/2021/adventencia.png')}}" class="w-100">
                                </div>
                                <BR>


                                <div class="col-12 text-center">
                                    <br>
                                    <button v-if="finalizar" class="btn btn-default col-8">
                                        <i class="fas fa-home" style="font-size: 30px"></i>
                                    </button>
                                    <br>
                                    <button class="btn btn-success col-8" @click="guardarInfoGeneralFin">
                                        <img src="{{asset('images/2021/guardar_1.png')}}" class="w-100">
                                    </button>
                                </div>
                            </div>
                            <br>
                            <div class="col-12 text-center">
                                <button class="prevBtn pull-right" @click="guardarLugar" type="button" style="background: transparent !important;border: 0px !important;}"><img src="{{asset('images/2021/anterior.png')}}" class="w-25"></button>
                            </div>
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

        Vue.component('vue-multiselect', window.VueMultiselect.default)

        Vue.component(VueQrcode.name, VueQrcode);

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
                        estudios: '',
                        mensaje: '',
                        sexo: '',
                    },
                    finalizar: false,
                    value: null,
                    intereses: ['Deportes','Cine','Espiritualidad','Bailar','Viajar','Música','Leer','Gastronomía','Animales','Idiomas','Astrología','Cantar','Futbol','Yoga','Arte','Politica','Negocios'],
                    genero: ['Hombre', 'Mujer'],
                    genero_2: ['Hetero', 'Gay', 'Bi', 'Trans'],
                    situacion: ['Casado(a)', 'Soltero(a)', 'Divorciado(a)','Viudo(a)','Union Libre'],
                    idiomas: ['Español', 'Ingles', 'Aleman', 'Japones', 'Chino', 'Portugues'],
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
                            usuario: this.user,
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
                    /*axios.post('{{url('/usuarios/guardaInfoGeneral')}}',*/
                    this.finalizar = true;
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                            usuario: this.user,
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
                guardarInfoGeneralFin: function(){
                    /*axios.post('{{url('/usuarios/guardaInfoGeneral')}}',*/
                    this.finalizar = true;
                    axios.post('{{url('/usuarios/guardaUbicacion')}}',
                        {
                            estado: this.filtros.estado,
                            ciudad: this.filtros.ciudad,
                            cp: this.filtros.cp,
                            colonia: this.filtros.colonia,
                            usuario: this.user,
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
                        window.location.href = '/home';
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
                        //this.estados.push(response.data);
                        console.log(response.data);
                        for(var e in response.data){
                            this.estados.push(response.data[e].estado);
                            console.log(e);
                        }
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
                        //this.ciudades.push(response.data);
                        for(var e in response.data){
                            this.ciudades.push(response.data[e].ciudad);
                            console.log(e);
                        }
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
                        //this.cps.push(response.data);
                        for(var e in response.data){
                            this.cps.push(response.data[e].cp);
                            console.log(e);
                        }
                    }).catch(function (error) {
                        console.log(error);
                        vm.errors = error.response;
                    });
                },
                getColonias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getColonias')}}', {cp:this.filtros.cp}).then((response) => {
                        this.colonias=[];
                        //this.colonias.push(response.data);
                        for(var e in response.data){
                            this.colonias.push(response.data[e].colonia);
                            console.log(e);
                        }
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
                            vm.mensaje = '<div class="text-success text-center"><i class="fas fa-check-circle"></i> Guardado correctamente.</div>';
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
                this.user.codigo_nuevo = this.user.codigo_nuevo;
                this.filtros.estado = this.user.estado;
                setTimeout(() => this.getCiudades() = false, 500);
                setTimeout(() => this.filtros.ciudad = this.user.ciudad, 700);
                setTimeout(() => this.getCPs() = false, 800);
                setTimeout(() => this.filtros.cp = this.user.cp, 1000);
                setTimeout(() => this.getColonias() = false, 1100);
                setTimeout(() => this.filtros.colonia = this.user.colonia, 1300);
                if (this.user.genero == 1){
                    this.sexo = 'Mujer';
                }else{
                    this.sexo = 'Hombre';
                }

            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
