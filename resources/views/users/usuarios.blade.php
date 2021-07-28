@extends('layouts.app')
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

        .inactivo{
            background-color: lightgray;
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
        <div>
            <div class="card mb-3">
                <div class="card-header"><i class="far fa-clipboard"></i> Personas en ACTON</div>
                <div class="card-body">
                    <div style="display: flex; flex-wrap: wrap">
                        <div class="col-sm-3">
                            <label>Nombre</label>
                            <input class="form-control" v-model="filtros.nombre" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-3">
                            <label>Codigo Personal</label>
                            <input class="form-control" v-model="filtros.codigo_personal" name="codigo_personal">
                        </div>
                        <div class="col-sm-3">
                            <label>Conexiones</label>
                            <select class="form-control" v-model="filtros.conexion" @keyup.enter="buscar">
                                <option></option>
                                <option>Siguiendo</option>
                                <option>Me siguen</option>
                                <option>Sin conexión</option>
                                <option>Tiendas</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Tiendas/GYM</label>
                            <select class="form-control" v-model="filtros.tiendagym" @keyup.enter="buscar">
                                <option></option>
                                <option v-for="p in this.tiendas[0]" :selected="p == filtros.tiendagym">@{{ p.name }}</option>
                            </select>
                        </div>
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
                            <label>&nbsp;</label>
                            <br>
                            <button class="btn btn-primary" @click="buscar" :disabled=" buscando" class="col-sm-12">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-search"></i>&nbsp;Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div v-for="usuario in usuarios.data" class="d-flex usuario">
                        <div class="col-4 d-flex flex-column align-items-start">
                            <span>
                                <i v-if="usuario.vigente" class="fa fa-user text-info"></i>
                                <i v-else class="fa fa-user text-default"></i>
                                @{{ usuario.name+' '+usuario.last_name }}
                            </span>
                            <!--span>@{{ usuario.medio }}</span-->
                        </div>
                        <div class="col-4 d-flex flex-column text-center">
                        </div>
                        <div class="col-4 d-flex flex-column align-items-end">
                            <div class="d-flex settings">
                                <div>
                                    <a v-tooltip="{content:'Ver perfil'}" class="btn btn-sm btn-default" :href="'{{ url('/cuenta/') }}/' + usuario.id">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </div>
                                <div v-if="usuario.amistad=='si'">
                                    <a v-tooltip="{content:'Ver reto'}" class="btn btn-sm btn-default" :href="'{{ url('/usuarios/imagenes') }}/' + usuario.id">
                                        <i class="fas fa-running"></i>
                                    </a>
                                </div>
                                <div v-if="usuario.amistad=='si'">
                                    <button v-tooltip="{content:'Dejar de Seguir'}" class="btn btn-sm btn-default" @click="dejar(usuario)">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                </div>
                                <div v-if="usuario.amistad=='no'">
                                    <button v-tooltip="{content:'Seguir'}" class="btn btn-sm btn-default" @click="sigue(usuario)">
                                        <i class="fas fa-handshake"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
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
                    compras:[]
                }
            },
            methods: {
                loaded: function (usuarios) {
                    this.usuarios = usuarios;
                    this.buscando = false;
                },
                buscar: function () {
                    this.buscando = true;
                    this.$refs.paginador.consultar(this.filtros);
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
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
