@extends('layouts.app_interiores_dos')
@section('header')
    <style>
        .usuario{
            padding: 5px;
            margin: 5px;
        }

        .settings a, .settings button{
            margin-left: 5px;
        }

        .inactivo{
            background-color: lightgray;
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

            </div>

            <div class="card mb-3">
                <div class="card-body"  id="lstUsuarios">
                    <div v-for="usuario in usuarios.data" class="usuario mb-2" style="border-bottom: 1px solid #c2c2c2; height: 75px;">
                        <div class="row ">
                            <span class="col-8">
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
                            <div class="col-4 mt-2 row" style="margin-bottom: -40px;">

                                <span v-if="usuario.dias==7" class="bg-warning" style="width: 20px; height: 20px; border-radius: 10px;"></span>
                                <span v-else-if="usuario.dias>usuario.dias_reto" class="bg-success" style="width: 20px; height: 20px; border-radius: 10px;"></span>
                                <span v-else-if="usuario.dias<usuario.dias_reto" class="bg-danger" style="width: 20px; height: 20px; border-radius: 10px;"></span>
                                <a v-tooltip="{content:'Ver info'}" class="btn btn-sm btn-danger" :href="'{{ url('/usuarios/referido/') }}/' + usuario.id" style="background: #9B0000 !important;margin-left: 3px; height: 30px">
                                    Ver info
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
                        <paginador ref="paginador" :url="'{{url('/usuarios/buscar-referidos')}}'" @loaded="loaded"></paginador>
                    </div>
                    <div class="col-12 text-center mt-5" style="margin-top: 40px; color: #666666;">
                        <div class="row"> <div class="bg-warning mr-2 "  style="width: 20px; height: 20px; border-radius: 10px;"></div> <span style="color: #666666;">Este perfil se encuentra en periodo de prueba</span></div>
                        <div class="row"> <div class="bg-success mr-2 "  style="width: 20px; height: 20px; border-radius: 10px; "></div> <span style="color: #666666;">Este perfil se encuentra activo</span></div>
                        <div class="row"> <div class="bg-danger mr-2"  style="width: 20px; height: 20px; border-radius: 10px; "></div> <span style="color: #666666;">Este perfil se encuentra inactivo</span></div>
                    </div>
                </div>
            </div>
            <modal ref="comisionModal" :title="'Pago de comisi??n a usuario'" @ok="pagar()" height="400" :oktext="'Pagar'">
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
                <h5>??Quiere desactivar dar de baja a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="seguir" title="Seguir" @ok="seguir">
                <h5>??Quiere seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="dejarseguir" title="DejarSeguir" @ok="dejarseguir">
                <h5>??Quiere dejar de seguir a @{{ usuario.name +' '+usuario.last_name }}?</h5>
            </modal>
            <modal ref="referenciasModal" :title="'Personas que han usado el c??digo : '+usuario.referencia" :showok="false" :showcancel="false">
                <table class="table">
                    <tr>
                        <th>Cliente</th>
                        <th>Correo</th>
                        <th>Fecha de inscripci??n</th>
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
                <span>Especifica cuantos d??as lleva el usuario en el reto : </span>
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
                    intereses: ['Deportes','Cine','Espiritualidad','Bailar','Viajar','M??sica','Leer','Gastronom??a','Animales','Idiomas','Astrolog??a','Cantar','Futbol','Yoga','Arte','Politica','Negocios'],
                    genero: ['Hombre', 'Mujer'],
                    genero_2: ['Hetero', 'Gay', 'Bi', 'Trans'],
                    situacion: ['Casado(a)', 'Soltero(a)', 'Divorciado(a)','Viudo(a)','Union Libre'],
                    idiomas: ['Espa??ol', 'Ingles', 'Aleman', 'Japones', 'Chino', 'Portugues'],
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
                var vm = this;
                this.buscar();
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
