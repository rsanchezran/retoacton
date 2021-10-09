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
        .modal-dialog {
            max-width: 100% !important;
            margin: 1.75rem auto;
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
                <div class="card-header"><i class="far fa-clipboard"></i> Usuarios</div>
                <div class="card-body">
                    <div style="display: flex; flex-wrap: wrap">
                        <div class="col-sm-3">
                            <label>Nombre</label>
                            <input class="form-control" v-model="filtros.nombre" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-3">
                            <label>Correo electrónico</label>
                            <input class="form-control" v-model="filtros.email" @keyup.enter="buscar">
                        </div>
                        <div class="col-sm-5">
                            <label>Fechas de reto</label>
                            <div class="d-flex ">
                                <datepicker class="col-sm-4" v-model="filtros.fecha_inicio"
                                            placeholder="fecha inicio"></datepicker>
                                <div class="btn-sm btn-default" style="padding-top: 7px; background-color: #F3f3f3">
                                    <span>A</span></div>
                                <datepicker class="col-sm-4" v-model="filtros.fecha_final"
                                            placeholder="fecha final"></datepicker>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <label>&nbsp;</label>
                            <br>
                            <button class="btn btn-light" @click="buscar" :disabled=" buscando">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-search"></i>&nbsp;Buscar
                            </button>
                            <button class="btn btn-light" @click="exportar" :disabled=" buscando">
                                <i v-if="buscando" class="fa fa-spinner fa-spin"></i>
                                <i v-else class="fas fa-file-excel"></i>&nbsp;Exportar
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
                            <span>@{{ usuario.email }}</span>
                            <span>@{{ usuario.telefono }}</span>
                            <span>@{{ usuario.medio }}</span>
                        </div>
                        <div class="col-4 d-flex flex-column text-center">

                        </div>
                        <div class="col-4 d-flex flex-column align-items-end">

                            <div>
                                <button v-tooltip="{content:'Validar solicitud'}" class="btn btn-sm btn-default" @click="agregarSemanas(usuario)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="usuarios.length == 0 || usuarios.data.length == 0" align="center">
                        <h6 colspan="6">[No hay datos para mostrar]</h6>
                    </div>
                    <div class="float-right">
                        <paginador ref="paginador" :url="'{{url('/usuarios/buscar/validar')}}'" @loaded="loaded"></paginador>
                    </div>
                </div>
            </div>
            <modal ref="agregarSaldo" title="DejarSeguir" @ok="aumentaSaldo">
                <h5>Actualización de saldo de @{{ usuario.name +' '+usuario.last_name }}</h5>
                <input type="text" class="form-control" v-model="usuario.saldoAumentado" @keyup.enter="aumentaSaldo">
            </modal>
            <modal ref="agregarSemanas" title=" Validar solicitud" @ok="aumentaSemanas">
                <h5>Vaidar solicitud de @{{ usuario.name +' '+usuario.last_name }}</h5>
                <img :src="'/images/' + usuario.id +'_1.png'" width="700px">
                <img :src="'/images/' + usuario.id +'_2.png'" width="700px">
                <button class="col-5 btn btn-success btn-lg" @click="Valida(usuario.id)">Validar</button>
                <button class="col-5 btn btn-danger btn-lg" @click="Rechazo(usuario.id)">Rechazar</button>
            </modal>
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
                        estado: '0',
                        ingresadosReto: ''
                    },
                    usuario: {
                        id: '',
                        nombre: '',
                        tarjeta: '',
                        saldo: '',
                        referencia: '',
                        dias_reto:'',
                        saldoAumentado: 0,
                        nuevaSemanas: 0
                    },
                    referencias:{
                        data:[]
                    },
                    pagos:[],
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
                bajar: function () {
                    axios.post('{{url('/usuarios/bajar')}}', this.usuario).then(function (response) {
                        if (response.data.status=='ok'){
                            window.location.href = response.data.redirect;
                        }
                    }).catch(function () {

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
                agregarSaldo: function (usuario, saldo) {
                    this.usuario = usuario;
                    this.usuario.saldoAumentado = saldo;
                    this.$refs.agregarSaldo.showModal();
                },
                agregarSemanas: function (usuario) {
                    this.usuario = usuario;
                    this.$refs.agregarSemanas.showModal();
                },
                aumentaSaldo: function (){
                    let vm = this;
                    axios.post('{{url('/usuarios/aumentarSaldo')}}', this.usuario).then(function (response) {
                        vm.$refs.agregarSaldo.closeModal();
                        vm.buscar();
                    });
                },
                aumentaSemanas: function (){
                    let vm = this;
                    axios.post('{{url('/usuarios/aumentarSemanas')}}', this.usuario).then(response => {
                        if (response.status === 200) {
                            vm.$refs.agregarSemanas.closeModal();
                            vm.buscar();
                        }
                    }).catch(error => {
                        //run this code always when status!==200
                    });
                },
                Valida: function (usuario) { //muestra la siguiente pregunta y cierra la anteriror
                    axios.post('{{url('encuesta/enviarValidacion1/')}}/'+usuario,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (respuesta) {
                            location.reload();
                        })
                        .catch(function (error) {
                        });
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                },
                Rechazo: function (usuario) { //muestra la siguiente pregunta y cierra la anteriror
                    axios.post('{{url('encuesta/enviarRechazo/')}}/'+usuario,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (respuesta) {
                            location.reload();
                        })
                        .catch(function (error) {
                        });
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }

            },
            mounted: function () {
                this.buscar();
            }
        });

        var vue = new Vue({
            el: '#vue'
        });
    </script>
@endsection
