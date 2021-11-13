@extends('layouts.app_interiores_dos')
@section('header')
    <style>
        .usuario{
            padding: 5px;
            margin: 5px;
            border-bottom: 1px solid lightgray;
        }

        .settings a, .settings button{
            margin-left: 5px;
        }
        .multiselect__tag, select{
            background: #cccccc !important;
        }
        .multiselect__single, select {
            background: transparent !important;
        }
        .multiselect__input, .multiselect__single, select {
            background: transparent !important;
        }
        .multiselect__option--highlight {
            background: #0080DD !important;
        }

        .inactivo{
            background-color: lightgray;
        }
        .multiselect__tags, select {
            min-height: 18px !important;
            font-size: 13px !important;
            border-radius: 13px !important;
        }
        .multiselect__tags, select {
            padding: 2px 40px 0 8px !important;
        }
        .multiselect__single, select {
            font-size: 13px !important;
        }
        .multiselect__tags, select{
            background: rgb(245,245,245) !important;
            background: linear-gradient(180deg, rgba(245,245,245,1) 35%, rgba(166,166,166,1) 100%) !important;
            margin-bottom: 5px;
        }
        .multiselect--disabled {
            background: transparent !important;
            pointer-events: none !important;
        }
        .multiselect--disabled .multiselect__current, .multiselect--disabled .multiselect__select, .multiselect__option--disabled {
            background: transparent !important;
            color: #a6a6a6 !important;
        }
        .form-control:disabled, .form-control[readonly] {
            background-color: #e9ecef !important;
            opacity: 0.5;
        }
        .usuario {
            border-bottom: 0px solid lightgray;
            margin-bottom: -40px;
        }
    </style>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
@endsection
@section('content')
    <div id="vue">
        <div class="container">
            <temp-retos></temp-retos>
        </div>
    </div>

    <template id="temp">
        <div>
            <div class="card mb-3" style="margin-top: 40%">
                <div class="card-body">
                    <h3 v-if="mostrarfiltros" class="text-center" style="color: #999">Busca personas con<br> las siguientes caracteristicas</h3>
                    <div style="" class="">
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-12">
                                <label>Buscar por</label>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <!--div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actsexo" style="width: 40px; height: 20px;">
                            </div-->
                            <div v-if="mostrarfiltros" class="col-12">
                                <select class="form-control" v-model="filtros.sexo" @keyup.enter="buscar" >
                                    <option value="">Sexo</option>
                                    <option value="1">Hombre</option>
                                    <option value="0">Mujer</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row">
                            <!--div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actorientacion" style="width: 40px; height: 20px;">
                            </div-->
                            <div v-if="mostrarfiltros" class="col-8 offset-2">
                                <label>Orientación</label>
                                <select class="form-control" v-model="filtros.orientacion" @keyup.enter="buscar">
                                    <option value="">Orientación</option>
                                    <option>Hetero</option>
                                    <option>Gay</option>
                                    <option>Bi</option>
                                    <option>Trans</option>
                                </select>
                            </div>
                        </div>
                        <br v-if="mostrarfiltros">
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-12">
                                <label>Cerca de mi</label>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-2" v-model="actestado" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <select class="form-control" v-model="filtros.estado" @keyup.enter="buscar" @change="getCiudades()" :disabled="actestado == false">
                                    <option value="">Estado</option>
                                    <option v-for="p in this.estados[0]">@{{ p.estado }}</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-2" v-model="actciudad" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <select class="form-control" v-model="filtros.ciudad" @keyup.enter="buscar" @change="getCPs()" :disabled="actciudad == false">
                                    <option value="">Ciudad</option>
                                    <option v-for="p in this.ciudades[0]">@{{ p.ciudad }}</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-2" v-model="actcp" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <select class="form-control" v-model="filtros.cp" @keyup.enter="buscar" @change="getColonias()" :disabled="actcp == false">
                                    <option value="">Codigo postal</option>
                                    <option v-for="p in this.cps[0]">@{{ p.cp }}</option>
                                </select>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-2" v-model="actcolonia" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <select class="form-control" v-model="filtros.colonia" @keyup.enter="buscar" :disabled="actcolonia == false">
                                    <option value="">Colonia</option>
                                    <option  v-for="p in this.colonias[0]">@{{ p.colonia }}</option>
                                </select>
                            </div>
                        </div>
                        <br v-if="mostrarfiltros">
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actinteres" style="width: 40px; height: 20px;">
                            </div>
                                <div v-if="mostrarfiltros" class="col-10">
                                    <label>Intereses</label>
                                    <vue-multiselect :disabled="actinteres == false" v-model="filtros.intereses" :options="intereses" :preselect-first="false" :multiple="true" placeholder="Buscar personas que les interese"  :preserve-search="false"></vue-multiselect>
                                </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actidioma" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <label>Idiomas</label>
                                <vue-multiselect :disabled="actidioma == false" v-model="filtros.idiomas" :options="idiomas" :preselect-first="false" :multiple="true" placeholder="Buscar personas que hablen:"  :preserve-search="false"></vue-multiselect>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actsituacion" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <label>Estatus</label>
                                <vue-multiselect :disabled="actsituacion == false" v-model="filtros.estatus" :options="situacion" :preselect-first="false" :multiple="true" placeholder="Buscar personas que esten en:"  :preserve-search="false"></vue-multiselect>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="row ">
                            <div v-if="mostrarfiltros" class="col-2">
                                <input type="checkbox" class="col-12 mt-3" v-model="actedad" style="width: 40px; height: 20px;">
                            </div>
                            <div v-if="mostrarfiltros" class="col-10">
                                <label>Edad entre</label>
                                <div class="row col-12">
                                    <input type="number" class="form-control col-5 mr-3" v-model="filtros.edad_inicio" @keyup.enter="buscar" :disabled="actedad == false"> y
                                    <input type="number" class="form-control col-5 ml-3" v-model="filtros.edad_fin" @keyup.enter="buscar" :disabled="actedad == false">
                                </div>
                            </div>
                        </div>
                        <div v-if="mostrarfiltros" class="col-sm-3">
                            <label>&nbsp;</label>
                            <br v-if="mostrarfiltros">
                            <button class="btn btn-primary" @click="buscar" :disabled=" buscando" class="col-sm-12">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-search"></i>&nbsp;Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="!mostrarfiltros" v-on:click="muestralosfiltros()" style="font-size: 20px"><i class="fas fa-filter"></i></div>
            <div class="card mb-3">
                <div class="card-body"  id="lstUsuarios">
                    <img v-if="mostrarfiltrosimg" src="{{asset('images/2021/personas.png')}}" style="width: 60%; margin-left: 20%;">
                    <div v-for="usuario in usuarios.data" class="usuario row" >
                        <div class="col-12 row d-flex flex-column align-items-start">
                            <span class="col-10">
                                <a :href="'/cuenta/'+usuario.id">
                                    <img :src="'/cuenta/getFotografia/'+usuario.id+'/343234'"
                                         style="
                                        height: 100px;
                                        min-height: 50px;
                                        height: 50px;
                                        width: 50px;
                                        border-radius: 30px;">
                                    @{{ usuario.name+' '+usuario.last_name }}</a>
                            </span>
                            <div class="col-3 mt-2">
                                <a v-tooltip="{content:'Seguir'}" class="btn btn-sm btn-danger" :href="'{{ url('/cuenta/') }}/' + usuario.id" style="background: #9B0000 !important;">
                                    Conocer
                                </a>
                            </div>
                            <!--span>@{{ usuario.medio }}</span-->
                        </div>
                        <!--div class="col-4 d-flex flex-column text-center">
                        </div>
                        <div class="col-4 d-flex flex-column align-items-end"-->
                            <!--div class="d-flex settings"-->
                                <!--div>
                                    <a v-tooltip="{content:'Ver perfil'}" class="btn btn-sm btn-default" :href="'{{ url('/cuenta/') }}/' + usuario.id">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div-->
                                <!--div v-if="usuario.amistad=='si'">
                                    <a v-tooltip="{content:'Ver reto'}" class="btn btn-sm btn-default" :href="'{{ url('/cuenta/') }}/' + usuario.id">
                                        <i class="fas fa-running"></i>
                                    </a>
                                </div>
                                <div v-if="usuario.amistad=='si'">
                                    <button v-tooltip="{content:'Dejar de Seguir'}" class="btn btn-sm btn-default" @click="dejar(usuario)">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </div-->
                            <!--/div>
                        </div-->
                    </div>
                    <div v-if="usuarios.length == 0 || usuarios.data.length == 0" align="center">
                        <h6 colspan="6">[No hay datos para mostrar]</h6>
                    </div>
                    <div class="float-right">
                        <paginador ref="paginador" :url="'{{url('/usuarios/buscarSeguir')}}'" @loaded="loaded"></paginador>
                    </div>
                </div>
            </div>
            <modal ref="comisionModal" :title="'Pago de comisión a usuario'" @ok="pagar()" height="400" :oktext="'Pagar'">
                <div class="d-flex flex-column">
                    <span><b>Email : </b>@{{ usuario.email }}</span>
                    <span><b>Nombre : </b>@{{ usuario.name }}</span>
                    <span><b>No. Tarjeta : </b>@{{ usuario.tarjeta==null?'[Este cliente no ha registrado su tarjeta]':usuario.tarjeta }}</span>
                    <span><b>Cantidad a pagar : </b> $<money :cantidad="''+usuario.pagar"></money></span>
                </div>
                <table class="table mt-2">
                    <tr v-for="compra in referencias.data" :class="(compra.activo?'inactivo':'')">
                        <td>
                            <i v-if="compra.activo" class="fa fa-minus"></i>
                            <i v-else class="fa fa-check"></i>
                            <span> @{{  compra.name+' '+compra.last_name }}</span>
                        </td>
                        <td><fecha :fecha="compra.created_at"></fecha></td>
                        <td><money :cantidad="compra.monto"></money></td>
                    </tr>
                </table>
                <div class="float-right">
                    <paginador ref="paginadorComision" :url="'{{url('/usuarios/verComprasByReferencia')}}'" @loaded="loadedComision"></paginador>
                </div>
            </modal>
            <modal ref="baja" title="Baja de usuario" @ok="bajar">
                <h5>¿Quiere desactivar dar de baja a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="seguir" title="Seguir" @ok="seguir">
                <h5>¿Quiere seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="dejarseguir" title="DejarSeguir" @ok="dejarseguir">
                <h5>¿Quiere dejar de seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="referenciasModal" :title="'Personas que han usado el código : '+usuario.referencia" :showok="false" :showcancel="false">
                <table class="table">
                    <tr>
                        <th>Cliente</th>
                        <th>Correo</th>
                        <th>Fecha de inscripción</th>
                        <th>Inicio del reto</th>
                    </tr>
                    <tr v-for="referencia in referencias.data">
                        <td>@{{ referencia.name+' '+referencia.last_name }}</td>
                        <td>@{{ referencia.email}}</td>
                        <td><fecha :fecha="referencia.fecha_inscripcion"></fecha></td>
                        <td><fecha :fecha="referencia.inicio_reto"></fecha></td>
                    </tr>
                </table>
                <div class="float-right">
                    <paginador ref="paginadorReferencias" :url="'{{url('/usuarios/verReferencias')}}'" @loaded="loadedReferencias"></paginador>
                </div>
            </modal>
            <modal ref="pagosModal" :title="'Pagos efectuados al usuario : '+usuario.name" :showok="false" :showcancel="false">
                <table class="table">
                    <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                    </tr>
                    <tr v-for="pago in pagos.data">
                        <td><fecha :fecha="pago.created_at"></fecha></td>
                        <td><money :cantidad="pago.monto"></money></td>
                    </tr>
                </table>
                <div class="float-right">
                    <paginador ref="paginadorPagos" :url="'{{url('/usuarios/verPagos')}}'" @loaded="loadedPagos"></paginador>
                </div>
            </modal>
            <modal ref="comprasModal" :title="'Compras realizadas por el usuario : '+usuario.name" :showok="false" :showcancel="false">
                <table class="table">
                    <tr>
                        <th>Fecha</th>
                        <th>Monto</th>
                    </tr>
                    <tr v-for="compra in compras.data">
                        <td><fecha :fecha="compra.created_at"></fecha></td>
                        <td><money :cantidad="compra.monto"></money></td>
                    </tr>
                </table>
                <div class="float-right">
                    <paginador ref="paginadorCompras" :url="'{{url('/usuarios/verCompras')}}'" @loaded="loadedCompras"></paginador>
                </div>
            </modal>
            <modal ref="cambiarDiasModal" title="Cambiar inicio de reto del usuario" @ok="cambiarDias">
                <span>Especifica cuantos días lleva el usuario en el reto : </span>
                <div class="col-sm-4">
                    <input type="text" class="form-control" v-model="usuario.dias_reto" />
                </div>
            </modal>
        </div>
    </template>

@endsection
@section('scripts')
    <script>

        Vue.component('vue-multiselect', window.VueMultiselect.default);

        Vue.component('temp-retos', {
            template: '#temp',
            props: [
                'nombre_prop',
                'conexion_prop',
                'estado_prop',
                'ciudad_prop',
                'cp_prop',
                'colonia_prop',
                'tienda_prop',
                'codigo_personal_prop'
            ],
            data: function () {
                return {
                    buscando: false,
                    usuarios: [],
                    filtros: {
                        nombre: '',
                        email: '',
                        fecha_inicio: '',
                        fecha_final: '',
                        saldo: '',
                        ingresados: '',
                        ciudad: '0',
                        cp: '0',
                        estado: '0',
                        colonia: '0',
                        tiendagym: '0',
                        conexion: '0',
                        ingresadosReto: '',
                        codigo_personal: '',
                        intereses: [],
                        orientacion: '',
                        sexo: '',
                        edad_inicio: '',
                        edad_fin: '',
                        estatus: [],
                        idiomas: [],
                    },
                    usuario: {
                        id: '',
                        nombre: '',
                        tarjeta: '',
                        saldo: '',
                        referencia: '',
                        dias_reto:''
                    },
                    referencias:{
                        data:[]
                    },
                    pagos:[],
                    estados:[],
                    ciudades:[],
                    cps:[],
                    colonias:[],
                    tiendas:[],
                    conexiones:[],
                    compras:[],
                    mostrarfiltros: true,
                    mostrarfiltrosimg: true,
                    actsexo: false,
                    actorientacion: false,
                    actestado: false,
                    actcp: false,
                    actcolonia: false,
                    actedad: false,
                    actsituacion: false,
                    actidioma: false,
                    actinteres: false,
                    actciudad: false,
                    intereses: ['Deportes','Cine','Espiritualidad','Bailar','Viajar','Música','Leer','Gastronomía','Animales','Idiomas','Astrología','Cantar','Futbol','Yoga','Arte','Politica','Negocios'],
                    genero: ['Hombre', 'Mujer'],
                    genero_2: ['Hetero', 'Gay', 'Bi', 'Trans'],
                    situacion: ['Casado(a)', 'Soltero(a)', 'Divorciado(a)','Viudo(a)','Union Libre', 'Abierto a conocer gente'],
                    idiomas: ['Español', 'Ingles', 'Aleman', 'Japones', 'Chino', 'Portugues'],
                }
            },
            methods: {
                loaded: function (usuarios) {
                    this.usuarios = usuarios;
                    this.buscando = false;
                },
                muestralosfiltros: function () {
                    var vm = this;
                    vm.mostrarfiltros = true;
                    /*
                    if(vm.mostrarfiltros){
                        vm.mostrarfiltros = false;
                    }else{
                    }*/
                },
                buscar: function () {
                    var vm = this;
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
                    vm.mostrarfiltros = false;
                },
                pagar: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/pagar')}}', this.usuario).then(function (respuesta) {
                        vm.$refs.comisionModal.closeModal();
                        vm.buscar();
                    }).catch(function (error) {
                        console.error('Error generado en la consulta' + error.response.data);
                    });
                },
                mostrarTarjeta: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.comisionModal.showModal();
                    Vue.nextTick(()=> {
                        this.$refs.paginadorComision.consultar(this.usuario);
                    });
                },
                confirmar: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.baja.showModal();
                },
                sigue: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.seguir.showModal();
                },
                dejar: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.dejarseguir.showModal();
                },
                bajar: function () {
                    axios.post('{{url('/usuarios/bajar')}}', this.usuario).then(function (response) {
                        if (response.data.status=='ok'){
                            window.location.href = response.data.redirect;
                        }
                    }).catch(function () {

                    });
                },
                seguir: function () {
                    axios.post('{{url('/usuarios/seguir')}}/'+this.usuario.id).then(function (response) {
                        location.reload();
                    }).catch(function () {
                        window.location.href = '{{ url('/usuarios/imagenes') }}/' + this.usuario.id;

                    });
                },
                dejarseguir: function () {
                    axios.post('{{url('/usuarios/dejar_seguir')}}/'+this.usuario.id).then(function (response) {
                        location.reload();
                    }).catch(function () {

                        location.reload();
                    });
                },
                confirmarDias: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.cambiarDiasModal.showModal();
                },
                cambiarDias: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/cambiarDias')}}', this.usuario).then(function (response) {
                        vm.$refs.cambiarDiasModal.closeModal();
                        vm.buscar();
                    });
                },
                getTiendas: function () {
                    let vm = this;
                    axios.post('{{url('/usuarios/getTiendas')}}').then((response) => {
                        this.tiendas=[];
                        this.tiendas.push(response.data);
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
                exportar: function () {
                    window.open('{{url('/usuarios/exportar')}}/'+JSON.stringify(this.filtros),'_blank');
                },
                verPagos: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.pagosModal.showModal();
                    Vue.nextTick(()=> {
                        this.$refs.paginadorPagos.consultar(this.usuario);
                    });
                },
                verCompras: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.comprasModal.showModal();
                    Vue.nextTick(()=> {
                        this.$refs.paginadorCompras.consultar(this.usuario);
                    });
                },
                verReferencias: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.referenciasModal.showModal();
                    Vue.nextTick(()=> {
                        this.$refs.paginadorReferencias.consultar(this.usuario);
                    });
                },
                loadedCompras: function (compras) {
                    this.compras = compras;
                },
                loadedPagos: function (pagos) {
                    this.pagos = pagos;
                },
                loadedReferencias: function (referencias) {
                    this.referencias = referencias;
                },
                loadedComision: function (referencias) {
                    let now = new Date().toISOString().substring(0,10);
                    this.referencias = referencias;
                    this.usuario.pagar = _.sumBy(referencias.data,function (compra) {
                        if(compra.created_at.substring(0,10) != now){
                            compra.activo = false;
                            return parseInt('{{env('COMISION')}}');
                        }else{
                            compra.activo = true;
                            return 0;
                        }
                    })
                },

            },
            mounted: function () {
                var vm = this;
                this.getEstados();
                this.getTiendas();
                this.filtros.nombre = '{{$nombre_prop}}';
                this.filtros.codigo_personal = '{{$codigo_personal_prop}}';
                this.filtros.estado = '{{$estado_prop}}';
                this.filtros.ciudad = '{{$ciudad_prop}}';
                this.filtros.cp = '{{$cp_prop}}';
                this.filtros.colonia = '{{$colonia_prop}}';
                this.filtros.tiendagym = '{{$tienda_prop}}';
                this.filtros.conexion = '{{$conexion_prop}}';
                let uri = window.location.search.substring(1);
                let params = new URLSearchParams(uri);
                if(params.get("_token")) {
                    this.buscar();
                }
                if(params.get("q")) {
                    var q = '';
                    vm.mostrarfiltros = false;
                    if(params.get('q') == 'siguen'){
                        q = 'Me siguen';
                    }else{
                        q = 'Siguiendo';
                    }
                    this.filtros.conexion = q;
                    this.buscar();
                }else{
                    vm.mostrarfiltrosimg = true;
                }
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
